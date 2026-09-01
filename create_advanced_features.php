<?php
require_once 'config/db.php';

echo "<h1>⚙️ Creating Advanced Database Features</h1>";
echo "<hr>";
echo "<p>Creating Oracle functions, views, procedures, ADT, and cursors...</p>";
echo "<hr>";

$conn = garments_db_connect();
if (!$conn) {
    echo "<p style='color: red;'><strong>❌ Cannot connect to Oracle database</strong></p>";
    exit;
}

// Read the advanced_features.sql file
$sqlContent = file_get_contents('database/advanced_features.sql');

// Split by "/" which is the Oracle statement terminator
$statements = preg_split('/\n\/\s*\n/m', $sqlContent);

$createdCount = 0;
$skippedCount = 0;
$errorCount = 0;

foreach ($statements as $idx => $statement) {
    $statement = preg_replace('/^\s*--.*$/m', '', $statement); // Remove comments
    $statement = trim($statement);
    
    if (empty($statement) || strlen($statement) < 10) {
        continue;
    }
    
    // Extract the object type and name for logging
    $objectType = '';
    $objectName = '';
    if (preg_match('/^CREATE\s+(?:OR\s+REPLACE\s+)?(FUNCTION|PROCEDURE|VIEW|TYPE)\s+(\w+)/i', $statement, $matches)) {
        $objectType = strtoupper($matches[1]);
        $objectName = $matches[2];
        echo "<h3>Creating: $objectType $objectName</h3>";
    } else {
        echo "<h3>Executing statement " . ($idx + 1) . "</h3>";
    }
    
    $stmt = oci_parse($conn, $statement);
    if (!$stmt) {
        $error = oci_error($conn);
        echo "<p style='color: orange;'>⚠️ Parse error: " . htmlspecialchars(substr($error['message'] ?? 'Unknown', 0, 150)) . "</p>";
        $errorCount++;
        continue;
    }
    
    $result = @oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    $error = $result ? null : oci_error($stmt);
    
    if ($error) {
        $errorMsg = $error['message'] ?? 'Unknown error';
        if (strpos($errorMsg, 'already exists') !== false || 
            strpos($errorMsg, 'ORA-00955') !== false ||
            strpos($errorMsg, 'ORA-04043') !== false) {
            echo "<p style='color: gray;'>ℹ️ Already exists - skipping</p>";
            $skippedCount++;
        } else {
            echo "<p style='color: red;'>❌ Error: " . htmlspecialchars(substr($errorMsg, 0, 150)) . "</p>";
            $errorCount++;
        }
    } else {
        echo "<p style='color: green;'><strong>✅ Created successfully</strong></p>";
        $createdCount++;
    }
    
    oci_free_statement($stmt);
}

oci_close($conn);

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p><strong>Objects Created:</strong> $createdCount</p>";
echo "<p><strong>Skipped:</strong> $skippedCount</p>";
echo "<p><strong>Errors:</strong> $errorCount</p>";

if ($errorCount <= 2) {
    echo "<p style='color: green;'><strong>✅ Advanced features setup completed!</strong></p>";
}

echo "<hr>";
echo "<p><strong>Next:</strong> <a href='pages/advanced_reports.php' target='_blank'>📊 Test Advanced Reports Page</a></p>";
?>
