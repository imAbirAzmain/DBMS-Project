<?php
require_once 'config/db.php';

echo "<h1>🔧 Creating Missing Database Tables</h1>";
echo "<hr>";

$conn = garments_db_connect();
if (!$conn) {
    echo "<p style='color: red;'><strong>❌ Cannot connect to Oracle database</strong></p>";
    exit;
}

// SQL for missing tables - these are the critical ones for the Orders page
$sqlStatements = [
    "CREATE TABLE Order_Style (
        Order_ID NUMBER(5),
        Style_ID NUMBER(5),
        Style_Name VARCHAR2(100),
        Color VARCHAR2(30),
        Size_Value VARCHAR2(20),
        CONSTRAINT PK_Order_Style PRIMARY KEY (Order_ID, Style_ID),
        CONSTRAINT FK_OrderStyle_Order FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID)
    )" => "Order_Style",
    
    "CREATE TABLE Rel_Order_OrderStyle (
        Order_ID NUMBER(5),
        Style_ID NUMBER(5),
        Quantity NUMBER(8),
        CONSTRAINT PK_Rel_Order_OrderStyle PRIMARY KEY (Order_ID, Style_ID),
        CONSTRAINT FK_ROOS_OrderStyle FOREIGN KEY (Order_ID, Style_ID) REFERENCES Order_Style(Order_ID, Style_ID),
        CONSTRAINT CHK_OrderStyle_Quantity CHECK (Quantity > 0)
    )" => "Rel_Order_OrderStyle",
];

$createdCount = 0;
$skippedCount = 0;
$errorCount = 0;

foreach ($sqlStatements as $sql => $tableName) {
    echo "<h2>Creating: $tableName</h2>";
    
    $stmt = oci_parse($conn, $sql);
    if (!$stmt) {
        $error = oci_error($conn);
        echo "<p style='color: red;'>❌ Parse error: " . htmlspecialchars($error['message']) . "</p>";
        $errorCount++;
        continue;
    }
    
    $result = @oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    $error = $result ? null : oci_error($stmt);
    
    if ($error) {
        $errorMsg = $error['message'] ?? '';
        if (strpos($errorMsg, 'already exists') !== false || strpos($errorMsg, 'ORA-00955') !== false) {
            echo "<p style='color: gray;'>ℹ️ Table already exists - skipping</p>";
            $skippedCount++;
        } else {
            echo "<p style='color: red;'>❌ Error: " . htmlspecialchars($errorMsg) . "</p>";
            $errorCount++;
        }
    } else {
        echo "<p style='color: green;'><strong>✅ Table created successfully</strong></p>";
        $createdCount++;
    }
    
    oci_free_statement($stmt);
}

// Now add seed data for the Order_Style and Rel_Order_OrderStyle tables
echo "<h2>Adding Seed Data</h2>";

$seedData = [
    "INSERT INTO Order_Style VALUES (1, 1, 'V-Neck T-Shirt', 'Navy Blue', 'M')" => "Order 1, Style 1",
    "INSERT INTO Order_Style VALUES (1, 2, 'Polo Shirt', 'White', 'L')" => "Order 1, Style 2",
    "INSERT INTO Order_Style VALUES (2, 3, 'Hoodie', 'Gray', 'XL')" => "Order 2, Style 3",
    "INSERT INTO Order_Style VALUES (2, 4, 'Sweater', 'Black', 'M')" => "Order 2, Style 4",
    "INSERT INTO Order_Style VALUES (3, 5, 'Dress Shirt', 'Light Blue', 'L')" => "Order 3, Style 5",
    "INSERT INTO Order_Style VALUES (4, 6, 'Jacket', 'Brown', 'XL')" => "Order 4, Style 6",
    "INSERT INTO Order_Style VALUES (5, 7, 'Jersey', 'Red', 'S')" => "Order 5, Style 7",
    "INSERT INTO Order_Style VALUES (6, 8, 'Cardigan', 'Maroon', 'M')" => "Order 6, Style 8",
    
    "INSERT INTO Rel_Order_OrderStyle VALUES (1, 1, 500)" => "Order 1, Style 1 - 500 units",
    "INSERT INTO Rel_Order_OrderStyle VALUES (1, 2, 300)" => "Order 1, Style 2 - 300 units",
    "INSERT INTO Rel_Order_OrderStyle VALUES (2, 3, 800)" => "Order 2, Style 3 - 800 units",
    "INSERT INTO Rel_Order_OrderStyle VALUES (2, 4, 400)" => "Order 2, Style 4 - 400 units",
    "INSERT INTO Rel_Order_OrderStyle VALUES (3, 5, 1500)" => "Order 3, Style 5 - 1500 units",
    "INSERT INTO Rel_Order_OrderStyle VALUES (4, 6, 500)" => "Order 4, Style 6 - 500 units",
    "INSERT INTO Rel_Order_OrderStyle VALUES (5, 7, 1200)" => "Order 5, Style 7 - 1200 units",
    "INSERT INTO Rel_Order_OrderStyle VALUES (6, 8, 700)" => "Order 6, Style 8 - 700 units",
];

$seedCreatedCount = 0;
$seedSkippedCount = 0;
$seedErrorCount = 0;

foreach ($seedData as $sql => $description) {
    $stmt = oci_parse($conn, $sql);
    if (!$stmt) {
        continue;
    }
    
    $result = @oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    $error = $result ? null : oci_error($stmt);
    
    if ($error) {
        $errorMsg = $error['message'] ?? '';
        if (strpos($errorMsg, 'unique constraint') !== false) {
            $seedSkippedCount++;
        } else {
            $seedErrorCount++;
        }
    } else {
        $seedCreatedCount++;
    }
    
    oci_free_statement($stmt);
}

echo "<p>Seed data: Created=$seedCreatedCount, Skipped=$seedSkippedCount, Errors=$seedErrorCount</p>";

oci_close($conn);

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p><strong>Tables Created:</strong> $createdCount</p>";
echo "<p><strong>Tables Skipped:</strong> $skippedCount</p>";
echo "<p><strong>Errors:</strong> $errorCount</p>";

if ($errorCount === 0) {
    echo "<p style='color: green;'><strong>✅ All missing tables created successfully!</strong></p>";
}

echo "<hr>";
echo "<p><strong>Next:</strong> <a href='pages/orders.php' target='_blank'>✨ Test the Orders Page</a></p>";
?>
