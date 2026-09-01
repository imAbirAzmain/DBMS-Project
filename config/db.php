<?php

function garments_db_config(): array
{
    $config = [
                'username' => getenv('ORACLE_DB_USERNAME') ?: 'TAZORCL', // <-- Change this to FAHIMSDQ
        'password' => getenv('ORACLE_DB_PASSWORD') ?: 'tazorcl', // <-- Enter your Oracle 11g password here
        'host' => getenv('ORACLE_DB_HOST') ?: 'localhost',
        'port' => getenv('ORACLE_DB_PORT') ?: '1521', // Leave as 1521 (APEX is on 8080, but DB connections use 1521)
        'sid' => getenv('ORACLE_DB_SID') ?: 'XE',
        'service' => getenv('ORACLE_DB_SERVICE') ?: '',
    ];

    return $config;
}

function garments_db_connect()
{
    if (!function_exists('oci_connect')) {
        error_log('Oracle connection failed: OCI8 extension is not installed or enabled in PHP.');
        return false;
    }

    $config = garments_db_config();
    $dsn = $config['host'] . ':' . $config['port'] . '/' . ($config['service'] ?: $config['sid']);

    $conn = @oci_connect($config['username'], $config['password'], $dsn, 'AL32UTF8');

    if (!$conn) {
        $error = oci_error();
        error_log('Oracle connection failed: ' . ($error['message'] ?? 'Unknown OCI8 error'));
        return false;
    }

    return $conn;
}

function garments_db_fetch_one($sql, array $params = []): ?array
{
    $conn = garments_db_connect();
    if (!$conn) {
        return null;
    }

    $stmt = oci_parse($conn, $sql);
    foreach ($params as $key => $value) {
        oci_bind_by_name($stmt, ':' . $key, $params[$key]);
    }

    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($conn);

    return $row ?: null;
}

function garments_db_fetch_all($sql, array $params = []): array
{
    $conn = garments_db_connect();
    if (!$conn) {
        return [];
    }

    $stmt = oci_parse($conn, $sql);
    foreach ($params as $key => $value) {
        oci_bind_by_name($stmt, ':' . $key, $params[$key]);
    }

    oci_execute($stmt);
    $rows = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $rows[] = $row;
    }
    oci_free_statement($stmt);
    oci_close($conn);

    return $rows;
}

/** Execute a data-changing statement and return a user-safe result. */
function garments_db_execute(string $sql, array $params = []): array
{
    $conn = garments_db_connect();
    if (!$conn) {
        return ['ok' => false, 'error' => 'The Oracle database is unavailable.'];
    }

    $stmt = oci_parse($conn, $sql);
    if (!$stmt) {
        oci_close($conn);
        return ['ok' => false, 'error' => 'Unable to prepare the database request.'];
    }

    foreach ($params as $key => $value) {
        oci_bind_by_name($stmt, ':' . ltrim((string) $key, ':'), $params[$key]);
    }

    $ok = @oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    $error = $ok ? null : oci_error($stmt);
    oci_free_statement($stmt);
    oci_close($conn);

    return $ok
        ? ['ok' => true]
        : ['ok' => false, 'error' => 'The record could not be saved. ' . ($error['message'] ?? '')];
}

function garments_db_next_id(string $table, string $column): int
{
    $allowed = [
        'Accounts' => 'Transaction_ID', 'BOM' => 'BOM_ID', 'Buyer' => 'Buyer_ID',
        'Final_Product' => 'Final_Product_ID', 'Inspection' => 'Inspection_ID',
        'Machinery' => 'Machine_ID', 'Material' => 'Material_ID', 'Orders' => 'Order_ID',
        'Packaging' => 'Package_ID', 'Payment' => 'Payment_ID', 'Production_Stage' => 'Stage_ID',
        'Shipment' => 'Shipment_ID', 'Supplier' => 'Supplier_ID',
    ];
    if (($allowed[$table] ?? null) !== $column) {
        throw new InvalidArgumentException('Unsupported identifier source.');
    }
    $row = garments_db_fetch_one("SELECT NVL(MAX($column), 0) + 1 AS next_id FROM $table");
    return (int) ($row['NEXT_ID'] ?? 1);
}
