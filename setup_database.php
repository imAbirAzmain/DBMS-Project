<?php
require_once 'config/db.php';

echo "<h1>🗂️ Garments Management - Database Schema Setup</h1>";
echo "<hr>";

// Get all SQL files in order
$sqlFiles = [
    'database/structure.sql' => 'Creating database schema (tables)',
    'database/seed.sql' => 'Populating seed data (employees, workers, etc.)',
    'database/advanced_features.sql' => 'Creating advanced features (views, functions, procedures)',
];

$conn = garments_db_connect();
if (!$conn) {
    echo "<p style='color: red;'><strong>❌ Cannot connect to Oracle database</strong></p>";
    exit;
}

$totalExecuted = 0;
$totalErrors = 0;
$totalSkipped = 0;

foreach ($sqlFiles as $filePath => $description) {
    echo "<h2>Executing: $description</h2>";
    
    if (!file_exists($filePath)) {
        echo "<p style='color: orange;'><strong>⚠️ File not found:</strong> $filePath</p>";
        continue;
    }
    
    $sqlContent = file_get_contents($filePath);
    
    // Split by forward slash "/" (Oracle statement terminator) and semicolon
    $statements = preg_split('/[\s]*[\;\/][\s]*(?=[\n\r])/m', $sqlContent);
    
    // Filter and clean statements
    $statements = array_filter(
        array_map(function($stmt) {
            // Remove comments and whitespace
            $stmt = preg_replace('/^\s*--.*$/m', '', $stmt);
            return trim($stmt);
        }, $statements),
        fn($stmt) => !empty($stmt)
    );
    
    echo "<p>Processing SQL statements...</p>";
    
    $executedCount = 0;
    $skippedCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement) || strlen($statement) < 5) {
            continue;
        }
        
        $stmt = oci_parse($conn, $statement);
        if (!$stmt) {
            $error = oci_error($conn);
            $errorMsg = $error['message'] ?? 'Unknown error';
            if (strpos($errorMsg, 'already exists') === false) {
                echo "<p style='color: orange;'>⚠️ Parse: " . htmlspecialchars(substr($errorMsg, 0, 80)) . "</p>";
                $errorCount++;
            }
            continue;
        }
        
        $result = @oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
        $error = $result ? null : oci_error($stmt);
        
        if ($error) {
            $errorMsg = $error['message'] ?? 'Unknown error';
            // Check if it's an "already exists" error or other non-critical error
            if (strpos($errorMsg, 'already exists') !== false || 
                strpos($errorMsg, 'ORA-00955') !== false ||
                strpos($errorMsg, 'ORA-04043') !== false) {
                // These are expected when re-running
                $skippedCount++;
                $totalSkipped++;
            } else {
                echo "<p style='color: red;'>❌ " . htmlspecialchars(substr($errorMsg, 0, 100)) . "</p>";
                $errorCount++;
                $totalErrors++;
            }
        } else {
            $executedCount++;
            $totalExecuted++;
        }
        
        oci_free_statement($stmt);
    }
    
    echo "<p style='color: green;'><strong>✅ Executed: $executedCount | Skipped: $skippedCount | Errors: $errorCount</strong></p>";
    echo "<hr>";
}

oci_close($conn);

echo "<h2>Summary</h2>";
echo "<p><strong>Total Created/Executed:</strong> $totalExecuted</p>";
echo "<p><strong>Total Skipped (already exist):</strong> $totalSkipped</p>";
echo "<p><strong>Total Errors:</strong> $totalErrors</p>";

if ($totalErrors === 0 || $totalErrors < 5) {
    echo "<p style='color: green;'><strong>✅ Database schema setup completed!</strong></p>";
} else {
    echo "<p style='color: orange;'><strong>⚠️ Setup completed with some errors</strong></p>";
}

echo "<hr>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ul>";
echo "<li><a href='pages/orders.php' target='_blank'>📊 Test Orders Page</a> - should show order data</li>";
echo "<li><a href='pages/advanced_reports.php' target='_blank'>📈 Test Advanced Reports</a> - Oracle features demo</li>";
echo "<li>If errors persist, check that all tables were created by running: <code>SELECT table_name FROM user_tables;</code></li>";
echo "</ul>";
?>
