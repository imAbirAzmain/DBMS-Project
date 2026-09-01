<?php
require_once 'config/db.php';

echo "<h1>📋 Database Table Inventory</h1>";
echo "<hr>";

$conn = garments_db_connect();
if (!$conn) {
    echo "<p style='color: red;'><strong>❌ Cannot connect to database</strong></p>";
    exit;
}

// Get all tables
$sql = "SELECT table_name FROM user_tables ORDER BY table_name";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

$tables = [];
while ($row = oci_fetch_assoc($stmt)) {
    $tables[] = $row['TABLE_NAME'];
}
oci_free_statement($stmt);

echo "<p><strong>Total tables found:</strong> " . count($tables) . "</p>";

echo "<h2>Tables Present in Database:</h2>";
echo "<ul>";
foreach ($tables as $table) {
    echo "<li><code>$table</code></li>";
}
echo "</ul>";

// Expected tables from schema
$expectedTables = [
    'Employee', 'Incharge', 'Incharge_Contact', 'Worker', 'Worker_Contact',
    'Production_Stage', 'Machinery', 'Costing', 'Orders', 'Material',
    'Inspection', 'Final_Product', 'Packaging', 'Shipment', 'Buyer',
    'Buyer_Contact', 'Payment', 'Accounts', 'BOM', 'Supplier',
    'Supplier_Contact', 'Order_Style', 'Rel_Worker_ProductionStage',
    'Rel_Inch_Worker_Stage', 'Rel_ProductionStage_Machinery', 'Rel_Machinery_Costing',
    'Rel_Costing_Order', 'Rel_Order_Material', 'Rel_ProductionStage_Inspection',
    'Rel_Inspection_FinalProduct', 'Rel_FinalProduct_Packaging', 'Rel_Packaging_Shipment',
    'Rel_Shipment_Buyer', 'Rel_Buyer_Payment', 'Rel_Buyer_Order', 'Rel_Costing_Payment',
    'Rel_Payment_Accounts', 'Rel_Accounts_Employee', 'Rel_Costing_BOM', 'Rel_Accounts_BOM',
    'Rel_Accounts_Supplier', 'Rel_Supplier_BOM_Material', 'Rel_Order_OrderStyle'
];

echo "<h2>Missing Tables:</h2>";
$missingTables = array_diff($expectedTables, $tables);
if (count($missingTables) === 0) {
    echo "<p style='color: green;'><strong>✅ All expected tables exist!</strong></p>";
} else {
    echo "<ul style='color: red;'>";
    foreach ($missingTables as $table) {
        echo "<li><code>$table</code></li>";
    }
    echo "</ul>";
}

// Get all views
$sql = "SELECT view_name FROM user_views ORDER BY view_name";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

$views = [];
while ($row = oci_fetch_assoc($stmt)) {
    $views[] = $row['VIEW_NAME'];
}
oci_free_statement($stmt);

echo "<h2>Views Present in Database:</h2>";
if (count($views) === 0) {
    echo "<p style='color: orange;'><strong>⚠️ No views found</strong></p>";
} else {
    echo "<ul>";
    foreach ($views as $view) {
        echo "<li><code>$view</code></li>";
    }
    echo "</ul>";
}

// Try the specific queries that are failing
echo "<h2>Testing Orders Query:</h2>";
$testSql = "SELECT COUNT(*) AS total FROM Orders";
$stmt = oci_parse($conn, $testSql);
$result = @oci_execute($stmt);
if ($result) {
    $row = oci_fetch_assoc($stmt);
    echo "<p style='color: green;'><strong>✅ Orders table query successful:</strong> " . $row['TOTAL'] . " orders</p>";
} else {
    $error = oci_error($stmt);
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . htmlspecialchars($error['message']) . "</p>";
}
oci_free_statement($stmt);

echo "<h2>Testing Rel_Order_OrderStyle Query:</h2>";
$testSql2 = "SELECT COUNT(*) AS total FROM Rel_Order_OrderStyle";
$stmt = oci_parse($conn, $testSql2);
$result = @oci_execute($stmt);
if ($result) {
    $row = oci_fetch_assoc($stmt);
    echo "<p style='color: green;'><strong>✅ Rel_Order_OrderStyle query successful:</strong> " . $row['TOTAL'] . " records</p>";
} else {
    $error = oci_error($stmt);
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . htmlspecialchars($error['message']) . "</p>";
}
oci_free_statement($stmt);

oci_close($conn);
?>
