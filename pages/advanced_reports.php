<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

garments_require_login('../login.php');

$pageTitle = 'Advanced DB Features';
$activePage = 'advanced-features';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';
$escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;

$featureData = [
    'function' => ['label' => 'Oracle Function', 'value' => 'GET_ORDER_COST', 'description' => 'Returns the costing total for an order from the Oracle function.'],
    'subquery' => ['label' => 'Subquery Report', 'value' => 'Workers Above Average Salary', 'description' => 'Uses a real Oracle subquery to compare each worker salary against the average.'],
    'view' => ['label' => 'Oracle View', 'value' => 'V_PRODUCTION_STATUS', 'description' => 'Reads the production status view from Oracle.'],
    'adt' => ['label' => 'ADT', 'value' => 'ADDRESS_OBJ', 'description' => 'Object type-backed address detail demonstration.'],
    'procedure' => ['label' => 'PL/SQL Procedure', 'value' => 'UPDATE_STAGE_PROGRESS', 'description' => 'Updates production stage progress in Oracle via a stored procedure.'],
    'cursor' => ['label' => 'Cursor', 'value' => 'GENERATE_PRODUCTION_SUMMARY', 'description' => 'Cursor-based production summary written to a report table.'],
    'exception' => ['label' => 'Exception Handling', 'value' => 'ADD_PAYMENT', 'description' => 'Raises an Oracle application error when paid amount exceeds total amount.'],
];

$conn = garments_db_connect();
$oracleFunctionResult = null;
$oracleSubqueryRows = [];
$oracleViewRows = [];
$oracleAdtRows = [];
$oracleProcedureRows = [];
$oracleCursorRows = [];
$oracleErrorMessage = 'Oracle database is not reachable. Configure config/db.php and Oracle OCI8 first.';

if ($conn) {
    $oracleErrorMessage = null;
    $functionStmt = oci_parse($conn, 'SELECT GET_ORDER_COST(:order_id) AS order_cost FROM DUAL');
    oci_bind_by_name($functionStmt, ':order_id', $orderId);
    if (!oci_execute($functionStmt)) {
        $error = oci_error($functionStmt);
        $oracleErrorMessage = $error['message'] ?? 'Unable to execute the Oracle function.';
    } else {
        $oracleFunctionResult = oci_fetch_assoc($functionStmt);
    }
    oci_free_statement($functionStmt);

    $subqueryStmt = oci_parse($conn, "
        SELECT e.Employee_ID, w.Name, e.Salary
        FROM Employee e
        JOIN Worker w ON w.Employee_ID = e.Employee_ID
        WHERE e.Salary > (
            SELECT AVG(e2.Salary)
            FROM Employee e2
            JOIN Worker w2 ON w2.Employee_ID = e2.Employee_ID
        )
        ORDER BY e.Salary DESC
    ");
    if (!oci_execute($subqueryStmt)) {
        $error = oci_error($subqueryStmt);
        $oracleErrorMessage ??= $error['message'] ?? 'Unable to execute the subquery.';
    } else {
        while ($row = oci_fetch_assoc($subqueryStmt)) {
            $oracleSubqueryRows[] = $row;
        }
    }
    oci_free_statement($subqueryStmt);

    $viewStmt = oci_parse($conn, 'SELECT * FROM V_PRODUCTION_STATUS ORDER BY Order_ID');
    if (!oci_execute($viewStmt)) {
        $error = oci_error($viewStmt);
        $oracleErrorMessage ??= $error['message'] ?? 'Unable to query the production view.';
    } else {
        while ($row = oci_fetch_assoc($viewStmt)) {
            $oracleViewRows[] = $row;
        }
    }
    oci_free_statement($viewStmt);

    $adtStmt = oci_parse($conn, "
        SELECT b.Buyer_ID, b.Name, a.Address_Obj.Street AS Street, a.Address_Obj.City AS City, a.Address_Obj.Country AS Country
        FROM Buyer_Address_Details a
        JOIN Buyer b ON b.Buyer_ID = a.Buyer_ID
        ORDER BY b.Buyer_ID
    ");
    if (!oci_execute($adtStmt)) {
        $error = oci_error($adtStmt);
        $oracleErrorMessage ??= $error['message'] ?? 'Unable to query the address object.';
    } else {
        while ($row = oci_fetch_assoc($adtStmt)) {
            $oracleAdtRows[] = $row;
        }
    }
    oci_free_statement($adtStmt);

    $procedureStmt = oci_parse($conn, 'SELECT * FROM Production_Stage ORDER BY Stage_ID');
    if (!oci_execute($procedureStmt)) {
        $error = oci_error($procedureStmt);
        $oracleErrorMessage ??= $error['message'] ?? 'Unable to load production stages.';
    } else {
        while ($row = oci_fetch_assoc($procedureStmt)) {
            $oracleProcedureRows[] = $row;
        }
    }
    oci_free_statement($procedureStmt);

    $cursorStmt = oci_parse($conn, 'SELECT * FROM Production_Summary_Log ORDER BY Stage_ID');
    if (!oci_execute($cursorStmt)) {
        $error = oci_error($cursorStmt);
        $oracleErrorMessage ??= $error['message'] ?? 'Unable to load the cursor summary.';
    } else {
        while ($row = oci_fetch_assoc($cursorStmt)) {
            $oracleCursorRows[] = $row;
        }
    }
    oci_free_statement($cursorStmt);

    oci_close($conn);
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="db-features-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Advanced DB Features</li>
                    </ol>
                </nav>
                <h1 id="db-features-heading">Advanced Oracle DB Features</h1>
                <p>These demonstrations use the Oracle objects defined in the project schema and the stored database features from the backend.</p>
            </div>
        </section>

        <?php if ($oracleErrorMessage): ?>
            <div class="alert alert-warning" role="alert"><?= $escape($oracleErrorMessage); ?></div>
        <?php endif; ?>

        <section class="row g-4">
            <?php foreach ($featureData as $key => $meta): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="dashboard-card h-100">
                        <div class="dashboard-card__header">
                            <div>
                                <p class="section-eyebrow">DB requirement</p>
                                <h2><?= $escape($meta['label']); ?></h2>
                            </div>
                        </div>
                        <p class="mb-3"><strong><?= $escape($meta['value']); ?></strong></p>
                        <p class="mb-0"><?= $escape($meta['description']); ?></p>
                    </article>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="dashboard-section">
            <article class="dashboard-card">
                <div class="dashboard-card__header">
                    <div>
                        <p class="section-eyebrow">Function output</p>
                        <h2>Order Cost Demo</h2>
                    </div>
                </div>
                <form class="advanced-query-form" method="get" action="advanced_reports.php">
                    <div class="advanced-query-field advanced-query-field--compact">
                        <label for="functionOrderId">Order ID</label>
                        <input class="form-control" id="functionOrderId" name="order_id" type="number" min="1" value="<?= $escape($orderId); ?>" inputmode="numeric" required>
                    </div>
                    <div class="advanced-query-action">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-play-fill" aria-hidden="true"></i> Run GET_ORDER_COST</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table dashboard-table">
                        <thead><tr><th>Order ID</th><th>Oracle Function Result</th></tr></thead>
                        <tbody>
                        <tr>
                            <td>#<?= $escape($orderId); ?></td>
                            <td><?= $oracleFunctionResult ? $escape(number_format((float) ($oracleFunctionResult['ORDER_COST'] ?? 0), 2)) : 'Not available'; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section class="dashboard-section">
            <div class="row g-4">
                <div class="col-12 col-xl-6">
                    <article class="dashboard-card h-100">
                        <div class="dashboard-card__header">
                            <div>
                                <p class="section-eyebrow">PL/SQL procedure</p>
                                <h2>Update Stage Progress</h2>
                            </div>
                        </div>
                        <p>Calls <code>UPDATE_STAGE_PROGRESS</code> and then reloads this page with the current stage data.</p>
                        <form class="row g-2 advanced-query-form advanced-query-form--legacy" data-backend-resource="advanced_stage_progress">
                            <div class="col-sm-5"><label class="advanced-query-label" for="procedureStageId">Stage ID</label><input class="form-control" id="procedureStageId" name="stage_id" type="number" min="1" placeholder="e.g. 3" required></div>
                            <div class="col-sm-4"><label class="visually-hidden" for="procedureProgress">Progress</label><input class="form-control" id="procedureProgress" name="progress" type="number" min="0" max="100" placeholder="0–100" required></div>
                            <div class="col-sm-3"><button class="btn btn-primary w-100" type="submit">Run</button></div>
                        </form>
                        <div class="table-responsive mt-3">
                            <table class="table dashboard-table mb-0"><thead><tr><th>Stage</th><th>Progress</th></tr></thead><tbody>
                            <?php if ($oracleProcedureRows): foreach ($oracleProcedureRows as $row): ?><tr><td><?= $escape($row['STAGE_NAME']); ?></td><td><?= $escape($row['STAGE_PROGRESS']); ?></td></tr><?php endforeach; else: ?><tr><td colspan="2">No stages found.</td></tr><?php endif; ?>
                            </tbody></table>
                        </div>
                    </article>
                </div>
                <div class="col-12 col-xl-6">
                    <article class="dashboard-card h-100">
                        <div class="dashboard-card__header">
                            <div>
                                <p class="section-eyebrow">Exception handling</p>
                                <h2>Payment Validation Demo</h2>
                            </div>
                        </div>
                        <p>Calls <code>ADD_PAYMENT</code> with an invalid payment. Oracle raises <code>ORA-20001</code>; no record is inserted.</p>
                        <form class="row g-2 advanced-query-form advanced-query-form--legacy" data-backend-resource="advanced_payment_exception" data-response-target="#advancedActionResult">
                            <div class="col-md-3"><label class="advanced-query-label" for="exceptionPaymentId">Test payment ID</label><input class="form-control" id="exceptionPaymentId" name="payment_id" type="number" min="1" value="99999" required></div>
                            <div class="col-md-3"><label class="advanced-query-label" for="exceptionTotalAmount">Total amount</label><input class="form-control" id="exceptionTotalAmount" name="total_amount" type="number" min="0" value="1000" required></div>
                            <div class="col-md-3"><label class="advanced-query-label" for="exceptionPaidAmount">Paid amount</label><input class="form-control" id="exceptionPaidAmount" name="paid_amount" type="number" min="0" value="1200" required></div>
                            <div class="col-md-3"><label class="advanced-query-label" for="exceptionPaymentMethod">Payment method</label><input class="form-control" id="exceptionPaymentMethod" name="payment_method" type="text" value="Demo" required></div>
                            <div class="col-md-6"><label class="advanced-query-label" for="exceptionPaymentDate">Payment date</label><input class="form-control" id="exceptionPaymentDate" name="payment_date" type="date" value="2026-09-01" required></div>
                            <div class="col-md-6"><button class="btn btn-outline-danger w-100" type="submit">Trigger Oracle Exception</button></div>
                        </form>
                        <div id="advancedActionResult" class="alert mt-3 mb-0" hidden role="alert"></div>
                    </article>
                </div>
            </div>
        </section>

        <section class="dashboard-section">
            <article class="dashboard-card">
                <div class="dashboard-card__header">
                    <div>
                        <p class="section-eyebrow">Subquery</p>
                        <h2>Workers Above Average Salary</h2>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table dashboard-table">
                        <thead><tr><th>Employee ID</th><th>Name</th><th>Salary</th></tr></thead>
                        <tbody>
                        <?php if ($oracleSubqueryRows): foreach ($oracleSubqueryRows as $row): ?>
                            <tr>
                                <td>#<?= $escape($row['EMPLOYEE_ID']); ?></td>
                                <td><?= $escape($row['NAME']); ?></td>
                                <td>৳<?= $escape(number_format((float) ($row['SALARY'] ?? 0), 2)); ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="3">No rows returned.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section class="dashboard-section">
            <article class="dashboard-card">
                <div class="dashboard-card__header">
                    <div>
                        <p class="section-eyebrow">View</p>
                        <h2>Production Status View</h2>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table dashboard-table">
                        <thead><tr><th>Order</th><th>Style</th><th>Stage</th><th>Progress</th><th>Lot</th></tr></thead>
                        <tbody>
                        <?php if ($oracleViewRows): foreach ($oracleViewRows as $row): ?>
                            <tr>
                                <td>#<?= $escape($row['ORDER_ID'] ?? ''); ?></td>
                                <td><?= $escape($row['STYLE_NAME'] ?? ''); ?></td>
                                <td><?= $escape($row['STAGE_NAME'] ?? ''); ?></td>
                                <td><?= $escape($row['STAGE_PROGRESS'] ?? ''); ?></td>
                                <td><?= $escape($row['LOT_NUMBER'] ?? ''); ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="5">No rows returned.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section class="dashboard-section">
            <article class="dashboard-card">
                <div class="dashboard-card__header">
                    <div>
                        <p class="section-eyebrow">ADT</p>
                        <h2>Buyer Address Object</h2>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table dashboard-table">
                        <thead><tr><th>Buyer</th><th>Street</th><th>City</th><th>Country</th></tr></thead>
                        <tbody>
                        <?php if ($oracleAdtRows): foreach ($oracleAdtRows as $row): ?>
                            <tr>
                                <td><?= $escape($row['NAME']); ?></td>
                                <td><?= $escape($row['STREET']); ?></td>
                                <td><?= $escape($row['CITY']); ?></td>
                                <td><?= $escape($row['COUNTRY']); ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="4">No ADT rows available.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <section class="dashboard-section">
            <article class="dashboard-card">
                <div class="dashboard-card__header">
                    <div>
                        <p class="section-eyebrow">Cursor summary</p>
                        <h2>Production Summary Log</h2>
                    </div>
                </div>
                <form class="advanced-query-form advanced-query-form--single" data-backend-resource="advanced_generate_summary">
                    <div class="advanced-query-action">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-play-fill" aria-hidden="true"></i> Run GENERATE_PRODUCTION_SUMMARY</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table dashboard-table">
                        <thead><tr><th>Stage</th><th>Progress</th><th>Assigned Workers</th></tr></thead>
                        <tbody>
                        <?php if ($oracleCursorRows): foreach ($oracleCursorRows as $row): ?>
                            <tr>
                                <td><?= $escape($row['STAGE_NAME']); ?></td>
                                <td><?= $escape($row['STAGE_PROGRESS']); ?></td>
                                <td><?= $escape($row['ASSIGNED_WORKERS']); ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="3">Cursor output not generated yet.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
