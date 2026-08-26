<?php
/**
 * Inspection frontend prototype.
 * Static records are based on the Inspection entity and its relationships
 * with Production_Stage and Final_Product from the supplied schema.
 */
$pageTitle = 'Inspection';
$activePage = 'inspection';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$inspectionMetrics = [
    ['label' => 'Total Inspections', 'value' => '4', 'detail' => 'Inspections logged this month', 'icon' => 'bi-patch-check', 'tone' => 'primary'],
    ['label' => 'Passed Units', 'value' => '3,745', 'detail' => 'Total units passed across all inspections', 'icon' => 'bi-check2-all', 'tone' => 'success'],
    ['label' => 'Failed Units', 'value' => '55', 'detail' => 'Total units failed inspection', 'icon' => 'bi-x-octagon', 'tone' => 'rose'],
    ['label' => 'Pending Inspections', 'value' => '2', 'detail' => 'For Finishing and Quality Check stages', 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
];

$inspections = [
    [
        'id' => '1', 'stage' => 'Cutting', 'stageId' => '1', 'passed' => '1000', 'failed' => '0',
        'remarks' => 'All units passed inspection.', 'status' => 'Completed', 'statusKey' => 'completed', 'statusClass' => 'success',
        'finalProductId' => 'N/A', 'date' => '03 Aug 2026',
    ],
    [
        'id' => '2', 'stage' => 'Sewing', 'stageId' => '2', 'passed' => '980', 'failed' => '20',
        'remarks' => 'Minor stitching errors on 20 units. Reworked.', 'status' => 'Completed', 'statusKey' => 'completed', 'statusClass' => 'success',
        'finalProductId' => 'N/A', 'date' => '06 Aug 2026',
    ],
    [
        'id' => '3', 'stage' => 'Embroidery', 'stageId' => '3', 'passed' => '965', 'failed' => '35',
        'remarks' => 'Color mismatch on 35 units. Sent for review.', 'status' => 'Action Required', 'statusKey' => 'action-required', 'statusClass' => 'warning',
        'finalProductId' => 'N/A', 'date' => '06 Aug 2026',
    ],
    [
        'id' => '4', 'stage' => 'Printing', 'stageId' => '4', 'passed' => '800', 'failed' => '0',
        'remarks' => 'Initial batch passed. Awaiting full production.', 'status' => 'In Progress', 'statusKey' => 'in-progress', 'statusClass' => 'primary',
        'finalProductId' => 'N/A', 'date' => '07 Aug 2026',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="inspection-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inspection</li>
                    </ol>
                </nav>
                <h1 id="inspection-heading">Inspection</h1>
                <p>Log and review quality control inspections for each production stage.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addInspectionModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Inspection</span>
            </button>
        </section>

        <section aria-label="Inspection overview">
            <div class="row g-3">
                <?php foreach ($inspectionMetrics as $metric): ?>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-card--<?= $escape($metric['tone']); ?>">
                            <div class="metric-card__topline">
                                <span class="metric-card__icon"><i class="bi <?= $escape($metric['icon']); ?>" aria-hidden="true"></i></span>
                                <span class="metric-card__trend"><i class="bi bi-arrow-up-right" aria-hidden="true"></i> Live</span>
                            </div>
                            <p class="metric-card__value"><?= $escape($metric['value']); ?></p>
                            <h2 class="metric-card__label"><?= $escape($metric['label']); ?></h2>
                            <p class="metric-card__detail"><?= $escape($metric['detail']); ?></p>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="dashboard-section" aria-labelledby="inspections-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Quality control log</p>
                        <h2 id="inspections-table-heading">All Inspections</h2>
                    </div>
                    <span class="card-period">4 inspection records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by stage or remarks" aria-label="Search inspections" data-table-search="#inspectionsTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter inspections by status" data-table-filter="#inspectionsTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="completed">Completed</option>
                            <option value="in-progress">In Progress</option>
                            <option value="action-required">Action Required</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table inspections-table align-middle mb-0" id="inspectionsTable" data-table-label="inspections">
                        <caption class="visually-hidden">Static inspection data with stage, quantities, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Inspection ID</th>
                                <th scope="col">Production Stage</th>
                                <th scope="col">Passed Quantity</th>
                                <th scope="col">Failed Quantity</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inspections as $inspection): ?>
                                <tr data-search-row data-status="<?= $escape($inspection['statusKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($inspection['id']); ?></span></td>
                                    <td><strong><?= $escape($inspection['stage']); ?></strong></td>
                                    <td><span class="text-success fw-bold"><?= $escape($inspection['passed']); ?></span></td>
                                    <td><span class="text-danger fw-bold"><?= $escape($inspection['failed']); ?></span></td>
                                    <td><span class="table-row-meta"><?= $escape($inspection['remarks']); ?></span></td>
                                    <td><span class="status-badge status-badge--<?= $escape($inspection['statusClass']); ?>"><?= $escape($inspection['status']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View Inspection #<?= $escape($inspection['id']); ?>"
                                                aria-label="View Inspection #<?= $escape($inspection['id']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewInspectionModal"
                                                data-inspection-id="#<?= $escape($inspection['id']); ?>"
                                                data-inspection-stage="<?= $escape($inspection['stage']); ?>"
                                                data-inspection-passed="<?= $escape($inspection['passed']); ?>"
                                                data-inspection-failed="<?= $escape($inspection['failed']); ?>"
                                                data-inspection-remarks="<?= $escape($inspection['remarks']); ?>"
                                                data-inspection-status="<?= $escape($inspection['status']); ?>"
                                                data-inspection-status-class="<?= $escape($inspection['statusClass']); ?>"
                                                data-inspection-date="<?= $escape($inspection['date']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit Inspection #<?= $escape($inspection['id']); ?>" aria-label="Edit Inspection #<?= $escape($inspection['id']); ?>" data-prototype-action="Edit Inspection #<?= $escape($inspection['id']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="7">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No inspections match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#inspectionsTable">Showing 1–<?= count($inspections); ?> of <?= count($inspections); ?> inspections</p>
                    <nav aria-label="Inspections pagination">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left" aria-hidden="true"></i></span></li>
                            <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                            <li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-right" aria-hidden="true"></i></span></li>
                        </ul>
                    </nav>
                </div>
            </article>
        </section>
    </div>
</main>

<!-- UI-only modal: Add Inspection -->
<div class="modal fade" id="addInspectionModal" tabindex="-1" aria-labelledby="addInspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <form data-inspections-form>
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Quality control</p>
                        <h2 class="modal-title fs-5" id="addInspectionModalLabel">Add New Inspection Record</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="inspectionStage">Production Stage</label>
                            <select class="form-select" id="inspectionStage" name="stage_id" required>
                                <option value="" selected disabled>Select a stage</option>
                                <option value="1">Cutting</option>
                                <option value="2">Sewing</option>
                                <option value="3">Embroidery</option>
                                <option value="4">Printing</option>
                                <option value="5">Finishing</option>
                                <option value="6">Quality Check</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="passedQuantity">Passed Quantity</label>
                            <input class="form-control" id="passedQuantity" name="passed_quantity" type="number" min="0" placeholder="e.g. 990" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="failedQuantity">Failed Quantity</label>
                            <input class="form-control" id="failedQuantity" name="failed_quantity" type="number" min="0" placeholder="e.g. 10" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="inspectionRemarks">Remarks</label>
                            <textarea class="form-control" id="inspectionRemarks" name="remarks" rows="3" placeholder="e.g. Minor defects found, sent for rework."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewInspectionModal" tabindex="-1" aria-labelledby="viewInspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Inspection details</p>
                    <h2 class="modal-title fs-5" id="viewInspectionModalLabel">Inspection <span data-inspection-detail="id">#1</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Inspection Status</span>
                    <span class="status-badge status-badge--success" data-inspection-detail="status">Completed</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Production Stage</dt><dd data-inspection-detail="stage">Cutting</dd></div>
                    <div><dt>Inspection Date</dt><dd data-inspection-detail="date">03 Aug 2026</dd></div>
                    <div><dt>Passed Quantity</dt><dd class="text-success" data-inspection-detail="passed">1000</dd></div>
                    <div><dt>Failed Quantity</dt><dd class="text-danger" data-inspection-detail="failed">0</dd></div>
                    <div class="detail-grid__wide"><dt>Remarks</dt><dd data-inspection-detail="remarks">All units passed inspection.</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this inspection"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Record</button>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast app-toast" id="prototypeToast" role="status" aria-live="polite" aria-atomic="true">
        <div class="toast-body d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
            <span data-toast-message>Frontend-only action</span>
            <button class="btn-close ms-auto" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>