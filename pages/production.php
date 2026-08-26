<?php
/**
 * Production Stages frontend prototype.
 * All values are local dummy data based on the supplied schema and demo data.
 */
$pageTitle = 'Production Stages';
$activePage = 'production';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$productionMetrics = [
    ['label' => 'Total Stages', 'value' => '6', 'detail' => 'All defined production stages', 'icon' => 'bi-diagram-3', 'tone' => 'primary'],
    ['label' => 'In Progress', 'value' => '2', 'detail' => 'Stages currently active', 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
    ['label' => 'Completed', 'value' => '3', 'detail' => 'Stages finished this month', 'icon' => 'bi-check2-circle', 'tone' => 'success'],
    ['label' => 'Assigned Workers', 'value' => '53', 'detail' => 'Total workers across all stages', 'icon' => 'bi-people', 'tone' => 'indigo'],
];

$productionStages = [
    [
        'id' => '1', 'name' => 'Cutting', 'progress' => '100%', 'progressValue' => 100, 'assignedWorkers' => '15',
        'startDate' => '01 Aug 2026', 'endDate' => '03 Aug 2026', 'status' => 'Completed', 'statusKey' => 'completed', 'statusClass' => 'success',
        'incharge' => 'Rahim Ahmed', 'inchargeId' => '101', 'machine' => 'Automatic Cutter', 'machineId' => 'M001',
    ],
    [
        'id' => '2', 'name' => 'Sewing', 'progress' => '68%', 'progressValue' => 68, 'assignedWorkers' => '20',
        'startDate' => '03 Aug 2026', 'endDate' => '10 Aug 2026', 'status' => 'In Progress', 'statusKey' => 'in-progress', 'statusClass' => 'primary',
        'incharge' => 'Rahim Ahmed', 'inchargeId' => '101', 'machine' => 'Industrial Sewing Machine', 'machineId' => 'M002',
    ],
    [
        'id' => '3', 'name' => 'Embroidery', 'progress' => '100%', 'progressValue' => 100, 'assignedWorkers' => '10',
        'startDate' => '04 Aug 2026', 'endDate' => '06 Aug 2026', 'status' => 'Completed', 'statusKey' => 'completed', 'statusClass' => 'success',
        'incharge' => 'Rahim Ahmed', 'inchargeId' => '101', 'machine' => 'Multi-head Embroidery Machine', 'machineId' => 'M003',
    ],
    [
        'id' => '4', 'name' => 'Printing', 'progress' => '32%', 'progressValue' => 32, 'assignedWorkers' => '8',
        'startDate' => '06 Aug 2026', 'endDate' => '12 Aug 2026', 'status' => 'Needs Attention', 'statusKey' => 'needs-attention', 'statusClass' => 'warning',
        'incharge' => 'Rahim Ahmed', 'inchargeId' => '101', 'machine' => 'Screen Printing Machine', 'machineId' => 'M004',
    ],
    [
        'id' => '5', 'name' => 'Finishing', 'progress' => '0%', 'progressValue' => 0, 'assignedWorkers' => '0',
        'startDate' => '10 Aug 2026', 'endDate' => '15 Aug 2026', 'status' => 'Pending', 'statusKey' => 'pending', 'statusClass' => 'muted',
        'incharge' => 'Rahim Ahmed', 'inchargeId' => '101', 'machine' => 'Steam Press', 'machineId' => 'M005',
    ],
    [
        'id' => '6', 'name' => 'Quality Check', 'progress' => '0%', 'progressValue' => 0, 'assignedWorkers' => '0',
        'startDate' => '15 Aug 2026', 'endDate' => '18 Aug 2026', 'status' => 'Pending', 'statusKey' => 'pending', 'statusClass' => 'muted',
        'incharge' => 'Rahim Ahmed', 'inchargeId' => '101', 'machine' => 'Inspection Table', 'machineId' => 'M006',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="production-stages-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Production Stages</li>
                    </ol>
                </nav>
                <h1 id="production-stages-heading">Production Stages</h1>
                <p>Monitor and manage all production stages, assigned workers, and progress.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addProductionStageModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Stage</span>
            </button>
        </section>

        <section aria-label="Production overview">
            <div class="row g-3">
                <?php foreach ($productionMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="production-stages-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Stage register</p>
                        <h2 id="production-stages-table-heading">All Production Stages</h2>
                    </div>
                    <span class="card-period">August 2026</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by stage name, worker, or machine" aria-label="Search production stages" data-table-search="#productionStagesTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter stages by status" data-table-filter="#productionStagesTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="completed">Completed</option>
                            <option value="in-progress">In Progress</option>
                            <option value="needs-attention">Needs Attention</option>
                            <option value="pending">Pending</option>
                        </select>
                        <select class="form-select" aria-label="Filter stages by incharge" data-table-filter="#productionStagesTable" data-filter-key="incharge">
                            <option value="all">All Incharges</option>
                            <option value="101">Rahim Ahmed</option>
                            <!-- Dynamically populate from database later -->
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table production-stages-table align-middle mb-0" id="productionStagesTable" data-table-label="production stages">
                        <caption class="visually-hidden">Static production stage data with progress, assigned workers, dates, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Stage ID</th>
                                <th scope="col">Stage Name</th>
                                <th scope="col">Progress</th>
                                <th scope="col">Assigned Workers</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productionStages as $stage): ?>
                                <tr data-search-row data-status="<?= $escape($stage['statusKey']); ?>" data-incharge="<?= $escape($stage['inchargeId']); ?>">
                                    <td><span class="table-id">#<?= $escape($stage['id']); ?></span></td>
                                    <td><strong><?= $escape($stage['name']); ?></strong></td>
                                    <td>
                                        <div class="progress stage-progress" role="progressbar" aria-label="<?= $escape($stage['name']); ?> progress" aria-valuenow="<?= (int) $stage['progressValue']; ?>" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-fill--<?= $escape($stage['progressValue']); ?>"></div>
                                        </div>
                                        <span class="table-row-meta"><?= $escape($stage['progress']); ?></span>
                                    </td>
                                    <td><?= $escape($stage['assignedWorkers']); ?></td>
                                    <td><?= $escape($stage['startDate']); ?></td>
                                    <td><?= $escape($stage['endDate']); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($stage['statusClass']); ?>"><?= $escape($stage['status']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View Stage #<?= $escape($stage['id']); ?>"
                                                aria-label="View Stage #<?= $escape($stage['id']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewProductionStageModal"
                                                data-stage-id="#<?= $escape($stage['id']); ?>"
                                                data-stage-name="<?= $escape($stage['name']); ?>"
                                                data-stage-progress="<?= $escape($stage['progress']); ?>"
                                                data-stage-assigned-workers="<?= $escape($stage['assignedWorkers']); ?>"
                                                data-stage-start-date="<?= $escape($stage['startDate']); ?>"
                                                data-stage-end-date="<?= $escape($stage['endDate']); ?>"
                                                data-stage-status="<?= $escape($stage['status']); ?>"
                                                data-stage-status-class="<?= $escape($stage['statusClass']); ?>"
                                                data-stage-incharge="<?= $escape($stage['incharge']); ?>"
                                                data-stage-machine="<?= $escape($stage['machine']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit Stage #<?= $escape($stage['id']); ?>" aria-label="Edit Stage #<?= $escape($stage['id']); ?>" data-prototype-action="Edit Stage #<?= $escape($stage['id']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button table-action-button--danger" type="button" title="Delete Stage #<?= $escape($stage['id']); ?>" aria-label="Delete Stage #<?= $escape($stage['id']); ?>" data-prototype-action="Delete Stage #<?= $escape($stage['id']); ?>">
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="8">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No production stages match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#productionStagesTable">Showing 1–<?= count($productionStages); ?> of <?= count($productionStages); ?> production stages</p>
                    <nav aria-label="Production stages pagination">
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

<!-- UI-only modal: Add Production Stage -->
<div class="modal fade" id="addProductionStageModal" tabindex="-1" aria-labelledby="addProductionStageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content app-modal">
            <form data-production-stage-form>
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Production management</p>
                        <h2 class="modal-title fs-5" id="addProductionStageModalLabel">Add New Production Stage</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="stageId">Stage ID</label>
                            <input class="form-control" id="stageId" name="stage_id" type="number" min="1" placeholder="e.g. 7" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="stageName">Stage Name</label>
                            <input class="form-control" id="stageName" name="stage_name" type="text" placeholder="e.g. Washing" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="assignedWorkers">Assigned Workers</label>
                            <input class="form-control" id="assignedWorkers" name="assigned_workers" type="number" min="0" placeholder="e.g. 12" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="stageProgress">Progress (%)</label>
                            <input class="form-control" id="stageProgress" name="stage_progress" type="number" min="0" max="100" placeholder="e.g. 50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="stageStartDate">Start Date</label>
                            <input class="form-control" id="stageStartDate" name="start_date" type="date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="stageEndDate">End Date</label>
                            <input class="form-control" id="stageEndDate" name="end_date" type="date">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="stageStatus">Status</label>
                            <select class="form-select" id="stageStatus" name="status" required>
                                <option value="" selected disabled>Select status</option>
                                <option value="pending">Pending</option>
                                <option value="in-progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="needs-attention">Needs Attention</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Stage</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewProductionStageModal" tabindex="-1" aria-labelledby="viewProductionStageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Production Stage details</p>
                    <h2 class="modal-title fs-5" id="viewProductionStageModalLabel"><span data-stage-detail="name">Cutting</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Current Status</span>
                    <span class="status-badge status-badge--primary" data-stage-detail="status">In Progress</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Stage ID</dt><dd data-stage-detail="id">#1</dd></div>
                    <div><dt>Progress</dt><dd data-stage-detail="progress">100%</dd></div>
                    <div><dt>Assigned Workers</dt><dd data-stage-detail="assignedWorkers">15</dd></div>
                    <div><dt>Start Date</dt><dd data-stage-detail="startDate">01 Aug 2026</dd></div>
                    <div><dt>End Date</dt><dd data-stage-detail="endDate">03 Aug 2026</dd></div>
                    <div><dt>Incharge</dt><dd data-stage-detail="incharge">Rahim Ahmed</dd></div>
                    <div class="detail-grid__wide"><dt>Associated Machine</dt><dd data-stage-detail="machine">Automatic Cutter</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this production stage"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Stage</button>
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