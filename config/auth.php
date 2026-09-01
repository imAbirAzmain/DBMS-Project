<?php

require_once __DIR__ . '/db.php';

function garments_session_start_safe(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function garments_current_user(): ?array
{
    garments_session_start_safe();

    if (empty($_SESSION['garments_user']) || !is_array($_SESSION['garments_user'])) {
        return null;
    }

    return $_SESSION['garments_user'];
}

function garments_login_user(array $user): void
{
    garments_session_start_safe();
    $_SESSION['garments_user'] = [
        'employee_id' => (string) ($user['EMPLOYEE_ID'] ?? $user['employee_id'] ?? ''),
        'role' => strtolower((string) ($user['POSITION'] ?? $user['role'] ?? 'worker')),
        'name' => (string) ($user['DISPLAY_NAME'] ?? $user['name'] ?? 'User'),
        'status' => (string) ($user['STATUS'] ?? $user['status'] ?? ''),
    ];
}

function garments_logout_user(): void
{
    garments_session_start_safe();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function garments_require_login(string $redirectTo = '../login.php'): void
{
    garments_session_start_safe();

    if (!garments_current_user()) {
        header('Location: ' . $redirectTo);
        exit;
    }
}

function garments_require_role(string $role, string $redirectTo = '../login.php'): void
{
    garments_require_login($redirectTo);
    $user = garments_current_user();

    if (!$user || strtolower((string) $user['role']) !== strtolower($role)) {
        header('Location: ' . $redirectTo . '?error=unauthorized');
        exit;
    }
}

function garments_require_incharge(string $redirectTo = '../login.php'): void
{
    garments_require_role('incharge', $redirectTo);
}

function garments_require_worker(string $redirectTo = '../login.php'): void
{
    garments_require_role('worker', $redirectTo);
}

function garments_csrf_token(): string
{
    garments_session_start_safe();
    if (empty($_SESSION['garments_csrf_token'])) {
        $_SESSION['garments_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['garments_csrf_token'];
}

function garments_verify_csrf(?string $token): bool
{
    garments_session_start_safe();
    return is_string($token)
        && !empty($_SESSION['garments_csrf_token'])
        && hash_equals($_SESSION['garments_csrf_token'], $token);
}

function garments_authenticate_employee(?string $employeeId, ?string $password, ?string $requestedRole = null): ?array
{
    $employeeId = trim((string) $employeeId);
    $password = (string) $password;
    $requestedRole = $requestedRole !== null ? strtolower(trim((string) $requestedRole)) : null;

    if ($employeeId === '' || $password === '') {
        error_log('Login rejected: empty employee ID or password.');
        return null;
    }

    $conn = garments_db_connect();
    if (!$conn) {
        error_log('Login rejected: Oracle connection failed for employee ID ' . $employeeId);
        return null;
    }

    $sql = "
        SELECT
            e.Employee_ID,
            e.Position,
            e.Status,
            e.Last_Login,
            e.Password,
            COALESCE(i.Name, w.Name) AS Display_Name
        FROM Employee e
        LEFT JOIN Incharge i ON i.Employee_ID = e.Employee_ID
        LEFT JOIN Worker w ON w.Employee_ID = e.Employee_ID
        WHERE e.Employee_ID = :employee_id
    ";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':employee_id', $employeeId);
    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($conn);

    if (!$row) {
        error_log('Login rejected: no matching Oracle employee row for employee ID ' . $employeeId);
        return null;
    }

    $storedPassword = (string) ($row['PASSWORD'] ?? '');
    $validPassword = str_starts_with($storedPassword, '$2y$')
        ? password_verify($password, $storedPassword)
        : hash_equals($storedPassword, $password);
    if (!$validPassword) {
        error_log('Login rejected: invalid password for employee ID ' . $employeeId);
        return null;
    }

    $role = strtolower(trim((string) ($row['POSITION'] ?? '')));
    if ($requestedRole !== null && $requestedRole !== '' && $requestedRole !== $role) {
        error_log('Login rejected: requested role mismatch. Requested=' . $requestedRole . ', DB=' . $role . ', employee_id=' . $employeeId);
        return null;
    }

    return [
        'EMPLOYEE_ID' => $row['EMPLOYEE_ID'],
        'POSITION' => ucfirst(strtolower($role)),
        'STATUS' => $row['STATUS'],
        'DISPLAY_NAME' => $row['DISPLAY_NAME'],
        'LAST_LOGIN' => $row['LAST_LOGIN'],
    ];
}
