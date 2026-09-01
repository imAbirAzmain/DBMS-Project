<?php
/**
 * Machinery frontend prototype.
 * Static records are based on the Machinery entity and its relationship
 * with Production_Stage from the supplied schema.
 */
$pageTitle = 'Machinery';
$activePage = 'machinery';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$machineryMetrics = [
    ['label' => 'Total Machines', 'value' => '6', 'detail' => 'All machinery records in the system', 'icon' => 'bi-gear', 'tone' => 'primary'],
    ['label' => 'Machines in Use', 'value' => '4', 'detail' => 'Currently assigned to a production stage', 'icon' => 'bi-play-circle', 'tone' => 'success'],
    ['label' => 'Idle Machines', 'value' => '2', 'detail' => 'Available for assignment', 'icon' => 'bi-pause-circle', 'tone' => 'warning'],
    ['label' => 'Machine Types', 'value' => '5', 'detail' => 'Cutting, Sewing, Embroidery, Printing, Finishing', 'icon' => 'bi-tags', 'tone' => 'indigo'],
];

$machinery = [
    [
        'id' => 'M001', 'name' => 'Automatic Cutter', 'type' => 'Cutting', 'typeKey' => 'cutting',
        'costPerUnit' => '1500000', 'quantity' => '2', 'status' => 'In Use', 'statusKey' => 'in-use', 'statusClass' => 'success',
        'currentStage' => 'Cutting', 'usedDuration' => '48 hours', 'usedCost' => '৳12,000',
    ],
    [
        'id' => 'M002', 'name' => 'Industrial Sewing Machine', 'type' => 'Sewing', 'typeKey' => 'sewing',
        'costPerUnit' => '80000', 'quantity' => '50', 'status' => 'In Use', 'statusKey' => 'in-use', 'statusClass' => 'success',
        'currentStage' => 'Sewing', 'usedDuration' => '120 hours', 'usedCost' => '৳25,000',
    ],
    [
        'id' => 'M003', 'name' => 'Multi-head Embroidery', 'type' => 'Embroidery', 'typeKey' => 'embroidery',
        'costPerUnit' => '2500000', 'quantity' => '1', 'status' => 'In Use', 'statusKey' => 'in-use', 'statusClass' => 'success',
        'currentStage' => 'Embroidery', 'usedDuration' => '72 hours', 'usedCost' => '৳18,000',
    ],
    [
        'id' => 'M004', 'name' => 'Screen Printing Machine', 'type' => 'Printing', 'typeKey' => 'printing',
        'costPerUnit' => '1200000', 'quantity' => '2', 'status' => 'In Use', 'statusKey' => 'in-use', 'statusClass' => 'success',
        'currentStage' => 'Printing', 'usedDuration' => '24 hours', 'usedCost' => '৳9,000',
    ],
    [
        'id' => 'M005', 'name' => 'Steam Press', 'type' => 'Finishing', 'typeKey' => 'finishing',
        'costPerUnit' => '50000', 'quantity' => '10', 'status' => 'Idle', 'statusKey' => 'idle', 'statusClass' => 'muted',
        'currentStage' => 'N/A', 'usedDuration' => '0 hours', 'usedCost' => '৳0',
    ],
    [
        'id' => 'M006', 'name' => 'Inspection Table', 'type' => 'Finishing', 'typeKey' => 'finishing',
        'costPerUnit' => '20000', 'quantity' => '5', 'status' => 'Idle', 'statusKey' => 'idle', 'statusClass' => 'muted',
        'currentStage' => 'N/A', 'usedDuration' => '0 hours', 'usedCost' => '৳0',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="machinery-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Machinery</li>
                    </ol>
                </nav>
                <h1 id="machinery-heading">Machinery</h1>
                <p>Manage all machinery assets, their types, costs, and current usage status.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addMachineryModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Machine</span>
            </button>
        </section>

        <section aria-label="Machinery overview">
            <div class="row g-3">
                <?php foreach ($machineryMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="machinery-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Asset register</p>
                        <h2 id="machinery-table-heading">All Machinery</h2>
                    </div>
                    <span class="card-period">6 machine records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by name or type" aria-label="Search machinery" data-table-search="#machineryTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter machinery by type" data-table-filter="#machineryTable" data-filter-key="type">
                            <option value="all">All types</option>
                            <option value="cutting">Cutting</option>
                            <option value="sewing">Sewing</option>
                            <option value="embroidery">Embroidery</option>
                            <option value="printing">Printing</option>
                            <option value="finishing">Finishing</option>
                        </select>
                        <select class="form-select" aria-label="Filter machinery by status" data-table-filter="#machineryTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="in-use">In Use</option>
                            <option value="idle">Idle</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table machinery-table align-middle mb-0" id="machineryTable" data-table-label="machinery">
                        <caption class="visually-hidden">Static machinery data with type, cost, quantity, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Machine ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Cost Per Unit</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Status</th>
                                <th scope="col">Current Stage</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($machinery as $machine): ?>
                                <tr data-search-row data-type="<?= $escape($machine['typeKey']); ?>" data-status="<?= $escape($machine['statusKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($machine['id']); ?></span></td>
                                    <td><strong><?= $escape($machine['name']); ?></strong></td>
                                    <td><span class="table-row-meta"><?= $escape($machine['type']); ?></span></td>
                                    <td>৳<?= $escape(number_format((float) $machine['costPerUnit'])); ?></td>
                                    <td><?= $escape($machine['quantity']); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($machine['statusClass']); ?>"><?= $escape($machine['status']); ?></span></td>
                                    <td><span class="table-row-meta"><?= $escape($machine['currentStage']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View <?= $escape($machine['name']); ?>"
                                                aria-label="View <?= $escape($machine['name']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewMachineryModal"
                                                data-machine-id="#<?= $escape($machine['id']); ?>"
                                                data-machine-name="<?= $escape($machine['name']); ?>"
                                                data-machine-type="<?= $escape($machine['type']); ?>"
                                                data-machine-cost-per-unit="৳<?= $escape(number_format((float) $machine['costPerUnit'])); ?>"
                                                data-machine-quantity="<?= $escape($machine['quantity']); ?>"
                                                data-machine-status="<?= $escape($machine['status']); ?>"
                                                data-machine-status-class="<?= $escape($machine['statusClass']); ?>"
                                                data-machine-current-stage="<?= $escape($machine['currentStage']); ?>"
                                                data-machine-used-duration="<?= $escape($machine['usedDuration']); ?>"
                                                data-machine-used-cost="<?= $escape($machine['usedCost']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit <?= $escape($machine['name']); ?>" aria-label="Edit <?= $escape($machine['name']); ?>" data-prototype-action="Edit <?= $escape($machine['name']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button table-action-button--danger" type="button" title="Delete <?= $escape($machine['name']); ?>" aria-label="Delete <?= $escape($machine['name']); ?>" data-prototype-action="Delete <?= $escape($machine['name']); ?>">
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
                                        <span>No machinery match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#machineryTable">Showing 1–<?= count($machinery); ?> of <?= count($machinery); ?> machinery</p>
                    <nav aria-label="Machinery pagination">
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

<!-- UI-only modal: Add Machinery -->
<div class="modal fade" id="addMachineryModal" tabindex="-1" aria-labelledby="addMachineryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
<form data-machinery-form data-backend-resource="machinery">
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Asset management</p>
                        <h2 class="modal-title fs-5" id="addMachineryModalLabel">Add New Machine</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="machineId">Machine ID</label>
                            <input class="form-control" id="machineId" name="machine_id" type="text" placeholder="e.g. M007" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="machineName">Name</label>
                            <input class="form-control" id="machineName" name="name" type="text" placeholder="e.g. Button Attaching Machine" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="machineType">Type</label>
                            <input class="form-control" id="machineType" name="type" type="text" placeholder="e.g. Sewing" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="machineQuantity">Quantity</label>
                            <input class="form-control" id="machineQuantity" name="quantity" type="number" min="1" placeholder="e.g. 5" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="machineCost">Cost Per Unit</label>
                            <input class="form-control" id="machineCost" name="cost_per_unit" type="number" min="0" placeholder="e.g. 65000" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Machine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewMachineryModal" tabindex="-1" aria-labelledby="viewMachineryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Machine details</p>
                    <h2 class="modal-title fs-5" id="viewMachineryModalLabel"><span data-machine-detail="name">Automatic Cutter</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Current Status</span>
                    <span class="status-badge status-badge--success" data-machine-detail="status">In Use</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Machine ID</dt><dd data-machine-detail="id">#M001</dd></div>
                    <div><dt>Type</dt><dd data-machine-detail="type">Cutting</dd></div>
                    <div><dt>Quantity</dt><dd data-machine-detail="quantity">2</dd></div>
                    <div><dt>Cost Per Unit</dt><dd data-machine-detail="costPerUnit">৳1,500,000</dd></div>
                    <div class="detail-grid__wide"><dt>Assigned Stage</dt><dd data-machine-detail="currentStage">Cutting</dd></div>
                    <div><dt>Used Duration</dt><dd data-machine-detail="usedDuration">48 hours</dd></div>
                    <div><dt>Incurred Cost</dt><dd data-machine-detail="usedCost">৳12,000</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this machine"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Machine</button>
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
