<?php
require_once 'config/db.php';
require_once 'config/auth.php';

echo "<h1>🔍 Garments Management - Database Diagnostics</h1>";
echo "<hr>";

// ============================================================================
// TEST 1: Check if OCI8 extension is loaded
// ============================================================================
echo "<h2>TEST 1: OCI8 Extension Status</h2>";
if (extension_loaded('oci8')) {
    echo "<p style='color: green;'><strong>✅ OCI8 is loaded</strong></p>";
} else {
    echo "<p style='color: red;'><strong>❌ OCI8 is NOT loaded</strong></p>";
    echo "<p style='color: orange;'>You need to enable the OCI8 extension in your php.ini file.</p>";
    exit;
}

// ============================================================================
// TEST 2: Check database configuration
// ============================================================================
echo "<h2>TEST 2: Database Configuration</h2>";
$config = garments_db_config();
echo "<table border='1' cellpadding='10'>";
echo "<tr style='background-color: #f0f0f0;'><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>Username</td><td><strong>" . htmlspecialchars($config['username']) . "</strong></td></tr>";
echo "<tr><td>Password</td><td>" . str_repeat("*", strlen($config['password'])) . "</td></tr>";
echo "<tr><td>Host</td><td><strong>" . htmlspecialchars($config['host']) . "</strong></td></tr>";
echo "<tr><td>Port</td><td><strong>" . htmlspecialchars($config['port']) . "</strong></td></tr>";
echo "<tr><td>SID</td><td><strong>" . htmlspecialchars($config['sid']) . "</strong></td></tr>";
echo "<tr><td>Service</td><td><strong>" . htmlspecialchars($config['service']) . "</strong></td></tr>";
$dsn = $config['host'] . ':' . $config['port'] . '/' . ($config['service'] ?: $config['sid']);
echo "<tr style='background-color: #fff3cd;'><td><strong>Connection String (DSN)</strong></td><td><strong>" . htmlspecialchars($dsn) . "</strong></td></tr>";
echo "</table>";

// ============================================================================
// TEST 3: Attempt Oracle Connection
// ============================================================================
echo "<h2>TEST 3: Oracle Connection Test</h2>";
$conn = garments_db_connect();
if ($conn) {
    echo "<p style='color: green;'><strong>✅ Successfully connected to Oracle database!</strong></p>";
    
    // Get Oracle version
    $stmt = oci_parse($conn, "SELECT BANNER FROM V\$VERSION WHERE ROWNUM <= 1");
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    echo "<p><strong>Oracle Version:</strong> " . htmlspecialchars($row['BANNER']) . "</p>";
    oci_free_statement($stmt);
    oci_close($conn);
} else {
    echo "<p style='color: red;'><strong>❌ Failed to connect to Oracle database</strong></p>";
    $error = oci_error();
    echo "<p style='color: darkred;'><strong>Error:</strong> " . htmlspecialchars($error['message'] ?? 'Unknown error') . "</p>";
    echo "<p style='color: orange;'><strong>Troubleshooting steps:</strong></p>";
    echo "<ul>";
    echo "<li>Ensure Oracle 19c is running</li>";
    echo "<li>Verify the SID or Service name is correct (currently using: " . htmlspecialchars($config['sid']) . ")</li>";
    echo "<li>Check username and password are correct</li>";
    echo "<li>Ensure firewall allows localhost:1521 connection</li>";
    echo "</ul>";
    exit;
}

// ============================================================================
// TEST 4: Check if Employee table exists
// ============================================================================
echo "<h2>TEST 4: Employee Table Existence</h2>";
$conn = garments_db_connect();
$sql = "SELECT COUNT(*) AS table_count FROM user_tables WHERE table_name = 'EMPLOYEE'";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);
oci_free_statement($stmt);

if ($row['TABLE_COUNT'] > 0) {
    echo "<p style='color: green;'><strong>✅ Employee table exists</strong></p>";
} else {
    echo "<p style='color: red;'><strong>❌ Employee table does NOT exist</strong></p>";
    echo "<p style='color: orange;'>Please run the SQL scripts in the database/ folder to create the schema.</p>";
    oci_close($conn);
    exit;
}

// ============================================================================
// TEST 5: Check Employee Data
// ============================================================================
echo "<h2>TEST 5: Employee Table Data</h2>";
$sql = "SELECT Employee_ID, Position, Status, Password FROM Employee ORDER BY Employee_ID";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
$rows = [];
while ($row = oci_fetch_assoc($stmt)) {
    $rows[] = $row;
}
oci_free_statement($stmt);

if (count($rows) === 0) {
    echo "<p style='color: red;'><strong>❌ No employee records found</strong></p>";
    echo "<p style='color: orange;'>Please run the seed.sql file to populate test data.</p>";
    oci_close($conn);
    exit;
} else {
    echo "<p style='color: green;'><strong>✅ Found " . count($rows) . " employee records</strong></p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr style='background-color: #f0f0f0;'><th>Employee ID</th><th>Position</th><th>Status</th><th>Password</th></tr>";
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($row['EMPLOYEE_ID']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($row['POSITION']) . "</td>";
        echo "<td>" . htmlspecialchars($row['STATUS']) . "</td>";
        echo "<td><code>" . htmlspecialchars($row['PASSWORD']) . "</code></td>";
        echo "</tr>";
    }
    echo "</table>";
}

// ============================================================================
// TEST 6: Test Authentication
// ============================================================================
echo "<h2>TEST 6: Authentication Test</h2>";
echo "<p>Testing login with Employee ID: 101, Password: pass101 (Incharge)</p>";

$testUser = garments_authenticate_employee('101', 'pass101', 'incharge');
if ($testUser) {
    echo "<p style='color: green;'><strong>✅ Authentication successful!</strong></p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr style='background-color: #f0f0f0;'><th>Field</th><th>Value</th></tr>";
    foreach ($testUser as $key => $value) {
        echo "<tr><td><strong>" . htmlspecialchars($key) . "</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'><strong>❌ Authentication failed</strong></p>";
    echo "<p style='color: orange;'>Check your password or employee ID.</p>";
}

// ============================================================================
// TEST 7: Check Incharge and Worker tables
// ============================================================================
echo "<h2>TEST 7: Incharge and Worker Tables</h2>";

$sql_incharge = "SELECT COUNT(*) AS count FROM Incharge";
$stmt = oci_parse($conn, $sql_incharge);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);
oci_free_statement($stmt);
$incharge_count = $row['COUNT'] ?? 0;

$sql_worker = "SELECT COUNT(*) AS count FROM Worker";
$stmt = oci_parse($conn, $sql_worker);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);
oci_free_statement($stmt);
$worker_count = $row['COUNT'] ?? 0;

echo "<p><strong>Incharge records:</strong> " . $incharge_count . "</p>";
echo "<p><strong>Worker records:</strong> " . $worker_count . "</p>";

if ($incharge_count === 0) {
    echo "<p style='color: orange;'>⚠️ No incharge records. Run seed.sql to populate test data.</p>";
}

if ($worker_count === 0) {
    echo "<p style='color: orange;'>⚠️ No worker records. Run seed.sql to populate test data.</p>";
}

oci_close($conn);

echo "<hr>";
echo "<p style='color: green;'><strong>✅ All tests completed!</strong></p>";
?>
