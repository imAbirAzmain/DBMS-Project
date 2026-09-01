<?php
/**
 * Packaging frontend prototype.
 * Static records are based on the Packaging entity and its relationship
 * with Final_Product from the supplied schema.
 */
$pageTitle = 'Packaging';
$activePage = 'packaging';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$packagingMetrics = [
    ['label' => 'Total Packages', 'value' => '4', 'detail' => 'Package groups created for shipment', 'icon' => 'bi-box2-heart', 'tone' => 'primary'],
    ['label' => 'Units Packaged', 'value' => '1,980', 'detail' => 'Total final product units packaged', 'icon' => 'bi-check2-square', 'tone' => 'success'],
    ['label' => 'Total Weight', 'value' => '594 KG', 'detail' => 'Combined weight of all packages', 'icon' => 'bi-speedometer2', 'tone' => 'teal'],
    ['label' => 'Ready for Shipment', 'value' => '2', 'detail' => 'Package groups awaiting shipment', 'icon' => 'bi-truck', 'tone' => 'indigo'],
];

$packaging = [
    [
        'id' => '1', 'date' => '04 Aug 2026', 'weightPerPack' => '15', 'quantityPerPack' => '50',
        'totalPackage' => '20', 'type' => 'Carton', 'typeKey' => 'carton', 'sourceLot' => 'LOT-CUT-001',
        'status' => 'Shipped', 'statusKey' => 'shipped', 'statusClass' => 'success',
    ],
    [
        'id' => '2', 'date' => '07 Aug 2026', 'weightPerPack' => '12', 'quantityPerPack' => '49',
        'totalPackage' => '20', 'type' => 'Carton', 'typeKey' => 'carton', 'sourceLot' => 'LOT-SEW-001',
        'status' => 'Shipped', 'statusKey' => 'shipped', 'statusClass' => 'success',
    ],
    [
        'id' => '3', 'date' => '07 Aug 2026', 'weightPerPack' => '0.5', 'quantityPerPack' => '100',
        'totalPackage' => '10', 'type' => 'Polybag', 'typeKey' => 'polybag', 'sourceLot' => 'LOT-EMB-001',
        'status' => 'In Warehouse', 'statusKey' => 'in-warehouse', 'statusClass' => 'primary',
    ],
    [
        'id' => '4', 'date' => '08 Aug 2026', 'weightPerPack' => '0.4', 'quantityPerPack' => '80',
        'totalPackage' => '10', 'type' => 'Polybag', 'typeKey' => 'polybag', 'sourceLot' => 'LOT-PRN-001',
        'status' => 'In Warehouse', 'statusKey' => 'in-warehouse', 'statusClass' => 'primary',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="packaging-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Packaging</li>
                    </ol>
                </nav>
                <h1 id="packaging-heading">Packaging</h1>
                <p>Manage packaging details for finished product lots before shipment.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addPackagingModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Package Group</span>
            </button>
        </section>

        <section aria-label="Packaging overview">
            <div class="row g-3">
                <?php foreach ($packagingMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="packaging-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Packaging log</p>
                        <h2 id="packaging-table-heading">All Package Groups</h2>
                    </div>
                    <span class="card-period">4 package groups</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by lot number or type" aria-label="Search packaging" data-table-search="#packagingTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter packages by type" data-table-filter="#packagingTable" data-filter-key="type">
                            <option value="all">All types</option>
                            <option value="carton">Carton</option>
                            <option value="polybag">Polybag</option>
                        </select>
                        <select class="form-select" aria-label="Filter packages by status" data-table-filter="#packagingTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="shipped">Shipped</option>
                            <option value="in-warehouse">In Warehouse</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table packaging-table align-middle mb-0" id="packagingTable" data-table-label="packaging groups">
                        <caption class="visually-hidden">Static packaging data with type, quantities, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Package ID</th>
                                <th scope="col">Source Lot</th>
                                <th scope="col">Package Date</th>
                                <th scope="col">Type</th>
                                <th scope="col">Qty / Pack</th>
                                <th scope="col">Total Packages</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($packaging as $package): ?>
                                <tr data-search-row data-type="<?= $escape($package['typeKey']); ?>" data-status="<?= $escape($package['statusKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($package['id']); ?></span></td>
                                    <td><strong><?= $escape($package['sourceLot']); ?></strong></td>
                                    <td><?= $escape($package['date']); ?></td>
                                    <td><span class="table-row-meta"><?= $escape($package['type']); ?></span></td>
                                    <td><?= $escape($package['quantityPerPack']); ?></td>
                                    <td><?= $escape($package['totalPackage']); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($package['statusClass']); ?>"><?= $escape($package['status']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View Package Group #<?= $escape($package['id']); ?>"
                                                aria-label="View Package Group #<?= $escape($package['id']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewPackagingModal"
                                                data-packaging-id="#<?= $escape($package['id']); ?>"
                                                data-packaging-source-lot="<?= $escape($package['sourceLot']); ?>"
                                                data-packaging-date="<?= $escape($package['date']); ?>"
                                                data-packaging-type="<?= $escape($package['type']); ?>"
                                                data-packaging-quantity-per-pack="<?= $escape($package['quantityPerPack']); ?>"
                                                data-packaging-total-package="<?= $escape($package['totalPackage']); ?>"
                                                data-packaging-weight-per-pack="<?= $escape($package['weightPerPack']); ?> KG"
                                                data-packaging-status="<?= $escape($package['status']); ?>"
                                                data-packaging-status-class="<?= $escape($package['statusClass']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit Package Group #<?= $escape($package['id']); ?>" aria-label="Edit Package Group #<?= $escape($package['id']); ?>" data-prototype-action="Edit Package Group #<?= $escape($package['id']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="8">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No package groups match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#packagingTable">Showing 1–<?= count($packaging); ?> of <?= count($packaging); ?> package groups</p>
                    <nav aria-label="Packaging pagination">
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

<!-- UI-only modal: Add Packaging -->
<div class="modal fade" id="addPackagingModal" tabindex="-1" aria-labelledby="addPackagingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
<form data-packaging-form data-backend-resource="packaging">
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Packaging & Shipment</p>
                        <h2 class="modal-title fs-5" id="addPackagingModalLabel">Add New Package Group</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="packagingLot">Source Final Product Lot</label>
                            <input class="form-control" id="packagingLot" name="final_product_id" type="text" placeholder="e.g. LOT-FIN-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="packagingDate">Package Date</label>
                            <input class="form-control" id="packagingDate" name="package_date" type="date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="packagingType">Type</label>
                            <input class="form-control" id="packagingType" name="type" type="text" placeholder="e.g. Carton" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="packagingWeight">Weight Per Pack (KG)</label>
                            <input class="form-control" id="packagingWeight" name="weight_per_pack" type="number" min="0" step="0.1" placeholder="e.g. 15.5" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="packagingQtyPerPack">Quantity Per Pack</label>
                            <input class="form-control" id="packagingQtyPerPack" name="quantity_per_pack" type="number" min="1" placeholder="e.g. 50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="packagingTotal">Total Packages</label>
                            <input class="form-control" id="packagingTotal" name="total_package" type="number" min="1" placeholder="e.g. 20" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Package Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewPackagingModal" tabindex="-1" aria-labelledby="viewPackagingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Packaging details</p>
                    <h2 class="modal-title fs-5" id="viewPackagingModalLabel">Package Group <span data-packaging-detail="id">#1</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Shipment Status</span>
                    <span class="status-badge status-badge--success" data-packaging-detail="status">Shipped</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Source Lot</dt><dd data-packaging-detail="sourceLot">LOT-CUT-001</dd></div>
                    <div><dt>Package Date</dt><dd data-packaging-detail="date">04 Aug 2026</dd></div>
                    <div><dt>Type</dt><dd data-packaging-detail="type">Carton</dd></div>
                    <div><dt>Weight / Pack</dt><dd data-packaging-detail="weightPerPack">15 KG</dd></div>
                    <div><dt>Quantity / Pack</dt><dd data-packaging-detail="quantityPerPack">50</dd></div>
                    <div><dt>Total Packages</dt><dd data-packaging-detail="totalPackage">20</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this package group"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Group</button>
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
