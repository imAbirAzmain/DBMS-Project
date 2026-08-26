<?php
/**
 * Final Products frontend prototype.
 * Static records are based on the Final_Product entity and its relationships
 * with Inspection and Packaging from the supplied schema.
 */
$pageTitle = 'Final Products';
$activePage = 'final-products';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$finalProductMetrics = [
    ['label' => 'Total Products', 'value' => '3,745', 'detail' => 'Finished units ready for packaging', 'icon' => 'bi-box-seam', 'tone' => 'primary'],
    ['label' => 'Grade A Products', 'value' => '3,500', 'detail' => 'Top quality finished units', 'icon' => 'bi-award', 'tone' => 'success'],
    ['label' => 'Lots Created', 'value' => '4', 'detail' => 'Lots generated from production stages', 'icon' => 'bi-collection', 'tone' => 'indigo'],
    ['label' => 'Awaiting Packaging', 'value' => '1,745', 'detail' => 'Units yet to be packaged', 'icon' => 'bi-box2', 'tone' => 'warning'],
];

$finalProducts = [
    [
        'id' => '1', 'grade' => 'A', 'gradeKey' => 'a', 'lotNumber' => 'LOT-CUT-001',
        'dateOfCompletion' => '03 Aug 2026', 'sourceInspectionId' => '1', 'quantity' => '1000',
        'status' => 'Packaged', 'statusKey' => 'packaged', 'statusClass' => 'success',
    ],
    [
        'id' => '2', 'grade' => 'A', 'gradeKey' => 'a', 'lotNumber' => 'LOT-SEW-001',
        'dateOfCompletion' => '06 Aug 2026', 'sourceInspectionId' => '2', 'quantity' => '980',
        'status' => 'Packaged', 'statusKey' => 'packaged', 'statusClass' => 'success',
    ],
    [
        'id' => '3', 'grade' => 'B', 'gradeKey' => 'b', 'lotNumber' => 'LOT-EMB-001',
        'dateOfCompletion' => '06 Aug 2026', 'sourceInspectionId' => '3', 'quantity' => '965',
        'status' => 'Awaiting Packaging', 'statusKey' => 'awaiting-packaging', 'statusClass' => 'warning',
    ],
    [
        'id' => '4', 'grade' => 'A', 'gradeKey' => 'a', 'lotNumber' => 'LOT-PRN-001',
        'dateOfCompletion' => '07 Aug 2026', 'sourceInspectionId' => '4', 'quantity' => '800',
        'status' => 'Awaiting Packaging', 'statusKey' => 'awaiting-packaging', 'statusClass' => 'warning',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="final-products-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Final Products</li>
                    </ol>
                </nav>
                <h1 id="final-products-heading">Final Products</h1>
                <p>Manage all finished goods that have passed inspection and are ready for packaging.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addFinalProductModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Product Lot</span>
            </button>
        </section>

        <section aria-label="Final product overview">
            <div class="row g-3">
                <?php foreach ($finalProductMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="final-products-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Inventory log</p>
                        <h2 id="final-products-table-heading">All Final Products</h2>
                    </div>
                    <span class="card-period">4 product lots</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by lot number" aria-label="Search final products" data-table-search="#finalProductsTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter products by grade" data-table-filter="#finalProductsTable" data-filter-key="grade">
                            <option value="all">All grades</option>
                            <option value="a">Grade A</option>
                            <option value="b">Grade B</option>
                        </select>
                        <select class="form-select" aria-label="Filter products by status" data-table-filter="#finalProductsTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="packaged">Packaged</option>
                            <option value="awaiting-packaging">Awaiting Packaging</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table final-products-table align-middle mb-0" id="finalProductsTable" data-table-label="final products">
                        <caption class="visually-hidden">Static final product data with grade, lot number, and completion date</caption>
                        <thead>
                            <tr>
                                <th scope="col">Product ID</th>
                                <th scope="col">Lot Number</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Date of Completion</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($finalProducts as $product): ?>
                                <tr data-search-row data-grade="<?= $escape($product['gradeKey']); ?>" data-status="<?= $escape($product['statusKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($product['id']); ?></span></td>
                                    <td><strong><?= $escape($product['lotNumber']); ?></strong></td>
                                    <td><span class="grade-badge">Grade <?= $escape($product['grade']); ?></span></td>
                                    <td><?= $escape($product['quantity']); ?></td>
                                    <td><?= $escape($product['dateOfCompletion']); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($product['statusClass']); ?>"><?= $escape($product['status']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View Product Lot #<?= $escape($product['id']); ?>"
                                                aria-label="View Product Lot #<?= $escape($product['id']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewFinalProductModal"
                                                data-product-id="#<?= $escape($product['id']); ?>"
                                                data-product-lot-number="<?= $escape($product['lotNumber']); ?>"
                                                data-product-grade="Grade <?= $escape($product['grade']); ?>"
                                                data-product-quantity="<?= $escape($product['quantity']); ?>"
                                                data-product-date-of-completion="<?= $escape($product['dateOfCompletion']); ?>"
                                                data-product-source-inspection-id="#<?= $escape($product['sourceInspectionId']); ?>"
                                                data-product-status="<?= $escape($product['status']); ?>"
                                                data-product-status-class="<?= $escape($product['statusClass']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit Product Lot #<?= $escape($product['id']); ?>" aria-label="Edit Product Lot #<?= $escape($product['id']); ?>" data-prototype-action="Edit Product Lot #<?= $escape($product['id']); ?>">
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
                                        <span>No final products match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#finalProductsTable">Showing 1–<?= count($finalProducts); ?> of <?= count($finalProducts); ?> final products</p>
                    <nav aria-label="Final products pagination">
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

<!-- UI-only modal: Add Final Product -->
<div class="modal fade" id="addFinalProductModal" tabindex="-1" aria-labelledby="addFinalProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <form data-final-products-form>
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Inventory management</p>
                        <h2 class="modal-title fs-5" id="addFinalProductModalLabel">Add New Product Lot</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="productLotNumber">Lot Number</label>
                            <input class="form-control" id="productLotNumber" name="lot_number" type="text" placeholder="e.g. LOT-FIN-001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productGrade">Grade</label>
                            <select class="form-select" id="productGrade" name="grade" required>
                                <option value="" selected disabled>Select grade</option>
                                <option value="A">Grade A</option>
                                <option value="B">Grade B</option>
                                <option value="C">Grade C</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productDate">Date of Completion</label>
                            <input class="form-control" id="productDate" name="date_of_completion" type="date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productInspectionId">Source Inspection ID</label>
                            <input class="form-control" id="productInspectionId" name="inspection_id" type="number" min="1" placeholder="e.g. 5" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Product Lot</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewFinalProductModal" tabindex="-1" aria-labelledby="viewFinalProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Final product details</p>
                    <h2 class="modal-title fs-5" id="viewFinalProductModalLabel">Lot <span data-product-detail="lotNumber">LOT-CUT-001</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Packaging Status</span>
                    <span class="status-badge status-badge--success" data-product-detail="status">Packaged</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Product ID</dt><dd data-product-detail="id">#1</dd></div>
                    <div><dt>Grade</dt><dd data-product-detail="grade">Grade A</dd></div>
                    <div><dt>Quantity</dt><dd data-product-detail="quantity">1000</dd></div>
                    <div><dt>Date of Completion</dt><dd data-product-detail="dateOfCompletion">03 Aug 2026</dd></div>
                    <div class="detail-grid__wide"><dt>Source Inspection ID</dt><dd data-product-detail="sourceInspectionId">#1</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this product lot"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Lot</button>
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