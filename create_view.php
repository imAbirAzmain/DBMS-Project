<?php
require_once 'config/db.php';

echo "<h1>🔧 Creating V_PRODUCTION_STATUS View</h1>";
echo "<hr>";

$conn = garments_db_connect();
if (!$conn) {
    echo "<p style='color: red;'><strong>❌ Cannot connect to Oracle database</strong></p>";
    exit;
}

$viewSql = "
CREATE OR REPLACE VIEW V_PRODUCTION_STATUS AS
SELECT
    o.Order_ID,
    o.Description,
    os.Style_Name,
    ps.Stage_Name,
    ps.Stage_Progress,
    i.Passed_Quantity,
    fp.Lot_Number,
    s.Tracking_Number
FROM Orders o
LEFT JOIN Rel_Order_OrderStyle roos ON roos.Order_ID = o.Order_ID
LEFT JOIN Order_Style os ON os.Order_ID = roos.Order_ID AND os.Style_ID = roos.Style_ID
LEFT JOIN Rel_ProductionStage_Inspection rpsi ON rpsi.Stage_ID = 1
LEFT JOIN Inspection i ON i.Inspection_ID = rpsi.Inspection_ID
LEFT JOIN Rel_Inspection_FinalProduct rifp ON rifp.Inspection_ID = i.Inspection_ID
LEFT JOIN Final_Product fp ON fp.Final_Product_ID = rifp.Final_Product_ID
LEFT JOIN Rel_FinalProduct_Packaging rfpp ON rfpp.Final_Product_ID = fp.Final_Product_ID
LEFT JOIN Packaging p ON p.Package_ID = rfpp.Package_ID
LEFT JOIN Rel_Packaging_Shipment rps ON rps.Package_ID = p.Package_ID
LEFT JOIN Shipment s ON s.Shipment_ID = rps.Shipment_ID
LEFT JOIN Rel_ProductionStage_Machinery rpstm ON rpstm.Stage_ID = 1
LEFT JOIN Production_Stage ps ON ps.Stage_ID = rpstm.Stage_ID
ORDER BY o.Order_ID
";

echo "<p>Creating view: V_PRODUCTION_STATUS</p>";

$stmt = oci_parse($conn, $viewSql);
if (!$stmt) {
    $error = oci_error($conn);
    echo "<p style='color: red;'><strong>❌ Parse error:</strong> " . htmlspecialchars($error['message']) . "</p>";
} else {
    $result = @oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    $error = $result ? null : oci_error($stmt);
    
    if ($error) {
        $errorMsg = $error['message'] ?? 'Unknown error';
        if (strpos($errorMsg, 'already exists') !== false || strpos($errorMsg, 'ORA-00955') !== false) {
            echo "<p style='color: gray;'>ℹ️ View already exists</p>";
        } else {
            echo "<p style='color: red;'><strong>❌ Error:</strong> " . htmlspecialchars($errorMsg) . "</p>";
        }
    } else {
        echo "<p style='color: green;'><strong>✅ View created successfully!</strong></p>";
    }
    
    oci_free_statement($stmt);
}

// Verify the view
echo "<h2>Testing View</h2>";
$testSql = "SELECT COUNT(*) AS count FROM V_PRODUCTION_STATUS";
$stmt = oci_parse($conn, $testSql);
if ($stmt) {
    $result = @oci_execute($stmt);
    if ($result) {
        $row = oci_fetch_assoc($stmt);
        echo "<p style='color: green;'><strong>✅ View query successful:</strong> " . $row['COUNT'] . " rows</p>";
    } else {
        $error = oci_error($stmt);
        echo "<p style='color: red;'><strong>❌ View query failed:</strong> " . htmlspecialchars($error['message']) . "</p>";
    }
    oci_free_statement($stmt);
}

oci_close($conn);

echo "<hr>";
echo "<p><strong>Next:</strong> <a href='pages/advanced_reports.php' target='_blank'>📊 Test Advanced Reports Page</a></p>";
?>
