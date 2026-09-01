<?php
/**
 * BOM (Bill of Materials) frontend prototype.
 * Static records are based on the BOM entity and its relationships
 * with Material and Supplier from the supplied schema.
 */
$pageTitle = 'BOM';
$activePage = 'bom';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$bomMetrics = [
    ['label' => 'Total BOMs', 'value' => '4', 'detail' => 'All Bill of Materials records', 'icon' => 'bi-list-check', 'tone' => 'primary'],
    ['label' => 'Approved BOMs', 'value' => '2', 'detail' => 'Ready for production', 'icon' => 'bi-check2-circle', 'tone' => 'success'],
    ['label' => 'Pending Review', 'value' => '1', 'detail' => 'Awaiting approval', 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
    ['label' => 'Total Bill Value', 'value' => '৳1.8M', 'detail' => 'Combined value of all BOMs', 'icon' => 'bi-cash', 'tone' => 'teal'],
];

// Dummy data for BOMs, including a structured representation of the ternary relationship
$boms = [
    [
        'id' => '1', 'description' => 'Polo Shirt - Summer Collection',
        'unitBill' => '1500', 'totalBill' => '1500000',
        'materialSummary' => '3 materials from 2 suppliers',
        'status' => 'Approved', 'statusKey' => 'approved', 'statusClass' => 'success',
        'materialsBreakdown' => [
            ['materialName' => 'Cotton Fabric', 'supplierName' => 'Square Textiles', 'quantity' => '1,000 meters', 'timeRequired' => '7 days'],
            ['materialName' => 'Sewing Thread', 'supplierName' => 'ABC Accessories', 'quantity' => '500 cones', 'timeRequired' => '5 days'],
            ['materialName' => 'Buttons', 'supplierName' => 'ABC Accessories', 'quantity' => '2,000 pieces', 'timeRequired' => '3 days'],
        ],
    ],
    [
        'id' => '2', 'description' => 'Denim Jeans - Casual Line',
        'unitBill' => '1800', 'totalBill' => '1800000',
        'materialSummary' => '4 materials from 2 suppliers',
        'status' => 'Approved', 'statusKey' => 'approved', 'statusClass' => 'success',
        'materialsBreakdown' => [
            ['materialName' => 'Denim Fabric', 'supplierName' => 'DBL Fabrics', 'quantity' => '800 meters', 'timeRequired' => '10 days'],
            ['materialName' => 'Heavy Duty Thread', 'supplierName' => 'ABC Accessories', 'quantity' => '300 cones', 'timeRequired' => '6 days'],
            ['materialName' => 'Zipper', 'supplierName' => 'Fashion Source', 'quantity' => '800 pieces', 'timeRequired' => '4 days'],
            ['materialName' => 'Rivets', 'supplierName' => 'Fashion Source', 'quantity' => '1,600 pieces', 'timeRequired' => '4 days'],
        ],
    ],
    [
        'id' => '3', 'description' => 'Winter Jacket - Outerwear',
        'unitBill' => '2500', 'totalBill' => '2500000',
        'materialSummary' => '5 materials from 3 suppliers',
        'status' => 'Pending Review', 'statusKey' => 'pending-review', 'statusClass' => 'warning',
        'materialsBreakdown' => [
            ['materialName' => 'Polyester Fabric', 'supplierName' => 'Global Textile Ltd.', 'quantity' => '1,200 meters', 'timeRequired' => '8 days'],
            ['materialName' => 'Insulation', 'supplierName' => 'Global Textile Ltd.', 'quantity' => '600 units', 'timeRequired' => '9 days'],
            ['materialName' => 'Zipper', 'supplierName' => 'Fashion Source', 'quantity' => '600 pieces', 'timeRequired' => '5 days'],
            ['materialName' => 'Buttons', 'supplierName' => 'Cotton World', 'quantity' => '1,800 pieces', 'timeRequired' => '4 days'],
            ['materialName' => 'Labels', 'supplierName' => 'Global Textile Ltd.', 'quantity' => '600 pieces', 'timeRequired' => '7 days'],
        ],
    ],
    [
        'id' => '4', 'description' => 'Basic T-Shirt - Economy Line',
        'unitBill' => '850', 'totalBill' => '850000',
        'materialSummary' => '2 materials from 1 supplier',
        'status' => 'Approved', 'statusKey' => 'approved', 'statusClass' => 'success',
        'materialsBreakdown' => [
            ['materialName' => 'Cotton Blend Fabric', 'supplierName' => 'Square Textiles', 'quantity' => '1,500 meters', 'timeRequired' => '6 days'],
            ['materialName' => 'Basic Thread', 'supplierName' => 'ABC Accessories', 'quantity' => '700 cones', 'timeRequired' => '4 days'],
        ],
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="bom-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">BOM</li>
                    </ol>
                </nav>
                <h1 id="bom-heading">Bill of Materials</h1>
                <p>Manage all Bill of Materials records, including material descriptions and costs.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addBomModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add BOM</span>
            </button>
        </section>

        <section aria-label="BOM overview">
            <div class="row g-3">
                <?php foreach ($bomMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="bom-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Materials planning</p>
                        <h2 id="bom-table-heading">All Bill of Materials</h2>
                    </div>
                    <span class="card-period">4 BOM records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by description or materials" aria-label="Search BOMs" data-table-search="#bomTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter BOMs by status" data-table-filter="#bomTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="approved">Approved</option>
                            <option value="pending-review">Pending Review</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table bom-table align-middle mb-0" id="bomTable" data-table-label="BOMs">
                        <caption class="visually-hidden">Static Bill of Materials data with description, unit bill, and total bill</caption>
                        <thead>
                            <tr>
                                <th scope="col">BOM ID</th>
                                <th scope="col">Description</th>
                                <th scope="col">Unit Bill</th>
                                <th scope="col">Total Bill</th>
                                <th scope="col">Material Sourcing</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($boms as $bom): ?>
                                <tr data-search-row data-status="<?= $escape($bom['statusKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($bom['id']); ?></span></td>
                                    <td><strong><?= $escape($bom['description']); ?></strong></td>
                                    <td>৳<?= $escape(number_format((float) $bom['unitBill'])); ?></td>
                                    <td>৳<?= $escape(number_format((float) $bom['totalBill'])); ?></td>                                    <td><span class="table-row-meta"><?= $escape($bom['materialSummary']); ?></span></td>
                                    <td><span class="status-badge status-badge--<?= $escape($bom['statusClass']); ?>"><?= $escape($bom['status']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View BOM #<?= $escape($bom['id']); ?>"
                                                aria-label="View BOM #<?= $escape($bom['id']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewBomModal"
                                                data-bom-id="#<?= $escape($bom['id']); ?>"
                                                data-bom-description="<?= $escape($bom['description']); ?>"
                                                data-bom-unit-bill="৳<?= $escape(number_format((float) $bom['unitBill'])); ?>"
                                                data-bom-total-bill="৳<?= $escape(number_format((float) $bom['totalBill'])); ?>"
                                                data-bom-materials-breakdown="<?= $escape(json_encode($bom['materialsBreakdown'])); ?>"
                                                data-bom-status="<?= $escape($bom['status']); ?>"
                                                data-bom-status-class="<?= $escape($bom['statusClass']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit BOM #<?= $escape($bom['id']); ?>" aria-label="Edit BOM #<?= $escape($bom['id']); ?>" data-prototype-action="Edit BOM #<?= $escape($bom['id']); ?>">
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
                                        <span>No BOMs match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#bomTable">Showing 1–<?= count($boms); ?> of <?= count($boms); ?> BOMs</p>
                    <nav aria-label="BOMs pagination">
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

<!-- UI-only modal: Add BOM -->
<div class="modal fade" id="addBomModal" tabindex="-1" aria-labelledby="addBomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
<form data-bom-form data-backend-resource="bom">
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Materials planning</p>
                        <h2 class="modal-title fs-5" id="addBomModalLabel">Add New Bill of Materials</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="bomId">BOM ID</label>
                            <input class="form-control" id="bomId" name="bom_id" type="number" min="1" placeholder="e.g. 4" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="bomDescription">Material Description</label>
                            <input class="form-control" id="bomDescription" name="material_description" type="text" placeholder="e.g. T-Shirt - Basic Model" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="bomUnitBill">Unit Bill</label>
                            <input class="form-control" id="bomUnitBill" name="unit_bill" type="number" min="0" step="0.01" placeholder="e.g. 850" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="bomTotalBill">Total Bill</label>
                            <input class="form-control" id="bomTotalBill" name="total_bill" type="number" min="0" step="0.01" placeholder="e.g. 850000" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add BOM</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewBomModal" tabindex="-1" aria-labelledby="viewBomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">BOM details</p>
                    <h2 class="modal-title fs-5" id="viewBomModalLabel">BOM <span data-bom-detail="id">#1</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>BOM Status</span>
                    <span class="status-badge status-badge--success" data-bom-detail="status">Approved</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Description</dt><dd data-bom-detail="description">Polo Shirt - Summer Collection</dd></div>
                    <div><dt>Unit Bill</dt><dd data-bom-detail="unitBill">৳1,500</dd></div>
                    <div><dt>Total Bill</dt><dd data-bom-detail="totalBill">৳1,500,000</dd></div>
                    <div class="detail-grid__wide">
                        <dt>Material Sourcing Breakdown</dt>
                        <dd data-bom-detail="materialsBreakdown">
                            <!-- Dynamically populated by JavaScript -->
                            <div class="bom-material-item">
                                <strong>Cotton Fabric</strong>
                                <span>from Square Textiles</span>
                                <small>1,000 meters · 7 days</small>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this BOM"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit BOM</button>
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
