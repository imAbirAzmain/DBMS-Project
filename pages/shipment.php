<?php
/**
 * Shipment frontend prototype.
 * Static records are based on the Shipment entity and its relationships
 * with Packaging and Buyer from the supplied schema.
 */
$pageTitle = 'Shipment';
$activePage = 'shipment';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$shipmentMetrics = [
    ['label' => 'Total Shipments', 'value' => '3', 'detail' => 'All shipment records in the system', 'icon' => 'bi-truck', 'tone' => 'primary'],
    ['label' => 'Delivered Shipments', 'value' => '1', 'detail' => 'Shipments successfully delivered', 'icon' => 'bi-check2-circle', 'tone' => 'success'],
    ['label' => 'In Transit', 'value' => '1', 'detail' => 'Shipments currently en route', 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
    ['label' => 'Total Value', 'value' => '৳1.2M', 'detail' => 'Estimated value of all shipments', 'icon' => 'bi-cash', 'tone' => 'teal'],
];

$shipments = [
    [
        'id' => '1', 'trackingNumber' => 'TRK-001-BD', 'estimatedDelivery' => '10 Aug 2026',
        'destination' => 'Germany', 'destinationKey' => 'germany', 'shippedDate' => '05 Aug 2026',
        'status' => 'Delivered', 'statusKey' => 'delivered', 'statusClass' => 'success',
        'sourcePackageId' => '#1', 'buyerName' => 'ABC Fashion', 'buyerId' => '1',
    ],
    [
        'id' => '2', 'trackingNumber' => 'TRK-002-US', 'estimatedDelivery' => '19 Aug 2026',
        'destination' => 'USA', 'destinationKey' => 'usa', 'shippedDate' => '06 Aug 2026',
        'status' => 'In Transit', 'statusKey' => 'in-transit', 'statusClass' => 'primary',
        'sourcePackageId' => '#2', 'buyerName' => 'Global Apparel', 'buyerId' => '2',
    ],
    [
        'id' => '3', 'trackingNumber' => 'TRK-003-CA', 'estimatedDelivery' => '22 Aug 2026',
        'destination' => 'Canada', 'destinationKey' => 'canada', 'shippedDate' => '07 Aug 2026',
        'status' => 'Pending Dispatch', 'statusKey' => 'pending-dispatch', 'statusClass' => 'muted',
        'sourcePackageId' => '#3', 'buyerName' => 'Fashion Forward', 'buyerId' => '3',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="shipment-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Shipment</li>
                    </ol>
                </nav>
                <h1 id="shipment-heading">Shipment</h1>
                <p>Manage all outgoing shipments, tracking, and delivery status.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addShipmentModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Shipment</span>
            </button>
        </section>

        <section aria-label="Shipment overview">
            <div class="row g-3">
                <?php foreach ($shipmentMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="shipment-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Shipment log</p>
                        <h2 id="shipment-table-heading">All Shipments</h2>
                    </div>
                    <span class="card-period">3 shipment records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by tracking number or destination" aria-label="Search shipments" data-table-search="#shipmentTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter shipments by status" data-table-filter="#shipmentTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="delivered">Delivered</option>
                            <option value="in-transit">In Transit</option>
                            <option value="pending-dispatch">Pending Dispatch</option>
                        </select>
                        <select class="form-select" aria-label="Filter shipments by destination" data-table-filter="#shipmentTable" data-filter-key="destination">
                            <option value="all">All Destinations</option>
                            <option value="germany">Germany</option>
                            <option value="usa">USA</option>
                            <option value="canada">Canada</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table shipment-table align-middle mb-0" id="shipmentTable" data-table-label="shipments">
                        <caption class="visually-hidden">Static shipment data with tracking number, destination, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Shipment ID</th>
                                <th scope="col">Tracking Number</th>
                                <th scope="col">Destination</th>
                                <th scope="col">Shipped Date</th>
                                <th scope="col">Estimated Delivery</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shipments as $shipment): ?>
                                <tr data-search-row data-status="<?= $escape($shipment['statusKey']); ?>" data-destination="<?= $escape($shipment['destinationKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($shipment['id']); ?></span></td>
                                    <td><strong><?= $escape($shipment['trackingNumber']); ?></strong></td>
                                    <td><?= $escape($shipment['destination']); ?></td>
                                    <td><?= $escape($shipment['shippedDate']); ?></td>
                                    <td><?= $escape($shipment['estimatedDelivery']); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($shipment['statusClass']); ?>"><?= $escape($shipment['status']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View Shipment #<?= $escape($shipment['id']); ?>"
                                                aria-label="View Shipment #<?= $escape($shipment['id']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewShipmentModal"
                                                data-shipment-id="#<?= $escape($shipment['id']); ?>"
                                                data-shipment-tracking-number="<?= $escape($shipment['trackingNumber']); ?>"
                                                data-shipment-estimated-delivery="<?= $escape($shipment['estimatedDelivery']); ?>"
                                                data-shipment-destination="<?= $escape($shipment['destination']); ?>"
                                                data-shipment-shipped-date="<?= $escape($shipment['shippedDate']); ?>"
                                                data-shipment-status="<?= $escape($shipment['status']); ?>"
                                                data-shipment-status-class="<?= $escape($shipment['statusClass']); ?>"
                                                data-shipment-source-package-id="<?= $escape($shipment['sourcePackageId']); ?>"
                                                data-shipment-buyer-name="<?= $escape($shipment['buyerName']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit Shipment #<?= $escape($shipment['id']); ?>" aria-label="Edit Shipment #<?= $escape($shipment['id']); ?>" data-prototype-action="Edit Shipment #<?= $escape($shipment['id']); ?>">
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
                                        <span>No shipments match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#shipmentTable">Showing 1–<?= count($shipments); ?> of <?= count($shipments); ?> shipments</p>
                    <nav aria-label="Shipment pagination">
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

<!-- UI-only modal: Add Shipment -->
<div class="modal fade" id="addShipmentModal" tabindex="-1" aria-labelledby="addShipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <form data-shipment-form>
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Logistics</p>
                        <h2 class="modal-title fs-5" id="addShipmentModalLabel">Add New Shipment</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="trackingNumber">Tracking Number</label>
                            <input class="form-control" id="trackingNumber" name="tracking_number" type="text" placeholder="e.g. TRK-004-UK" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="destination">Destination</label>
                            <input class="form-control" id="destination" name="destination" type="text" placeholder="e.g. United Kingdom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="shippedDate">Shipped Date</label>
                            <input class="form-control" id="shippedDate" name="shipped_date" type="date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="estimatedDelivery">Estimated Delivery</label>
                            <input class="form-control" id="estimatedDelivery" name="estimated_delivery" type="date">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="sourcePackageId">Source Package ID</label>
                            <input class="form-control" id="sourcePackageId" name="package_id" type="text" placeholder="e.g. #5" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="buyerId">Buyer ID</label>
                            <input class="form-control" id="buyerId" name="buyer_id" type="number" min="1" placeholder="e.g. 4" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Shipment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewShipmentModal" tabindex="-1" aria-labelledby="viewShipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Shipment details</p>
                    <h2 class="modal-title fs-5" id="viewShipmentModalLabel">Shipment <span data-shipment-detail="id">#1</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Shipment Status</span>
                    <span class="status-badge status-badge--success" data-shipment-detail="status">Delivered</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Tracking Number</dt><dd data-shipment-detail="trackingNumber">TRK-001-BD</dd></div>
                    <div><dt>Destination</dt><dd data-shipment-detail="destination">Germany</dd></div>
                    <div><dt>Shipped Date</dt><dd data-shipment-detail="shippedDate">05 Aug 2026</dd></div>
                    <div><dt>Estimated Delivery</dt><dd data-shipment-detail="estimatedDelivery">10 Aug 2026</dd></div>
                    <div><dt>Source Package ID</dt><dd data-shipment-detail="sourcePackageId">#1</dd></div>
                    <div><dt>Buyer</dt><dd data-shipment-detail="buyerName">ABC Fashion</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this shipment"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Shipment</button>
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