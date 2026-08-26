<?php
/**
 * Materials frontend prototype.
 * Static records are based on the Material entity from the supplied schema.
 */
$pageTitle = 'Materials';
$activePage = 'materials';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$materialMetrics = [
    ['label' => 'Total Materials', 'value' => '6', 'detail' => 'All material records in the system', 'icon' => 'bi-box-seam', 'tone' => 'primary'],
    ['label' => 'Material Types', 'value' => '4', 'detail' => 'Fabric, Thread, Accessories, and Label', 'icon' => 'bi-tags', 'tone' => 'indigo'],
    ['label' => 'Avg. Unit Price', 'value' => '৳1.2K', 'detail' => 'Mean price across all material units', 'icon' => 'bi-cash', 'tone' => 'teal'],
    ['label' => 'Linked to BOMs', 'value' => '6', 'detail' => 'All materials are mapped to a BOM', 'icon' => 'bi-link-45deg', 'tone' => 'orange'],
];

$materials = [
    [
        'id' => '1', 'name' => 'Cotton Fabric', 'type' => 'Fabric', 'typeKey' => 'fabric',
        'unitOfMeasure' => 'meters', 'unitPrice' => '450', 'supplier' => 'Square Textiles',
    ],
    [
        'id' => '2', 'name' => 'Polyester Fabric', 'type' => 'Fabric', 'typeKey' => 'fabric',
        'unitOfMeasure' => 'meters', 'unitPrice' => '550', 'supplier' => 'DBL Fabrics',
    ],
    [
        'id' => '3', 'name' => 'Sewing Thread', 'type' => 'Thread', 'typeKey' => 'thread',
        'unitOfMeasure' => 'cones', 'unitPrice' => '150', 'supplier' => 'ABC Accessories',
    ],
    [
        'id' => '4', 'name' => 'Buttons', 'type' => 'Accessories', 'typeKey' => 'accessories',
        'unitOfMeasure' => 'pieces', 'unitPrice' => '5', 'supplier' => 'Cotton World',
    ],
    [
        'id' => '5', 'name' => 'Zipper', 'type' => 'Accessories', 'typeKey' => 'accessories',
        'unitOfMeasure' => 'pieces', 'unitPrice' => '25', 'supplier' => 'Fashion Source',
    ],
    [
        'id' => '6', 'name' => 'Neck Label', 'type' => 'Label', 'typeKey' => 'label',
        'unitOfMeasure' => 'pieces', 'unitPrice' => '8', 'supplier' => 'Global Textile Ltd.',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="materials-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Materials</li>
                    </ol>
                </nav>
                <h1 id="materials-heading">Materials</h1>
                <p>Manage all raw materials, their types, units of measure, and pricing.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Material</span>
            </button>
        </section>

        <section aria-label="Material overview">
            <div class="row g-3">
                <?php foreach ($materialMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="materials-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Material inventory</p>
                        <h2 id="materials-table-heading">All Materials</h2>
                    </div>
                    <span class="card-period">6 material records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by name or supplier" aria-label="Search materials" data-table-search="#materialsTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter materials by type" data-table-filter="#materialsTable" data-filter-key="type">
                            <option value="all">All types</option>
                            <option value="fabric">Fabric</option>
                            <option value="thread">Thread</option>
                            <option value="accessories">Accessories</option>
                            <option value="label">Label</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table materials-table align-middle mb-0" id="materialsTable" data-table-label="materials">
                        <caption class="visually-hidden">Static material data with type, unit of measure, and unit price</caption>
                        <thead>
                            <tr>
                                <th scope="col">Material ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Unit of Measure</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Primary Supplier</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $material): ?>
                                <tr data-search-row data-type="<?= $escape($material['typeKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($material['id']); ?></span></td>
                                    <td><strong><?= $escape($material['name']); ?></strong></td>
                                    <td><span class="table-row-meta"><?= $escape($material['type']); ?></span></td>
                                    <td><?= $escape($material['unitOfMeasure']); ?></td>
                                    <td>৳<?= $escape(number_format((float) $material['unitPrice'])); ?></td>
                                    <td><span class="table-row-meta"><?= $escape($material['supplier']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View <?= $escape($material['name']); ?>"
                                                aria-label="View <?= $escape($material['name']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewMaterialModal"
                                                data-material-id="#<?= $escape($material['id']); ?>"
                                                data-material-name="<?= $escape($material['name']); ?>"
                                                data-material-type="<?= $escape($material['type']); ?>"
                                                data-material-unit-of-measure="<?= $escape($material['unitOfMeasure']); ?>"
                                                data-material-unit-price="৳<?= $escape(number_format((float) $material['unitPrice'])); ?>"
                                                data-material-supplier="<?= $escape($material['supplier']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit <?= $escape($material['name']); ?>" aria-label="Edit <?= $escape($material['name']); ?>" data-prototype-action="Edit <?= $escape($material['name']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button table-action-button--danger" type="button" title="Delete <?= $escape($material['name']); ?>" aria-label="Delete <?= $escape($material['name']); ?>" data-prototype-action="Delete <?= $escape($material['name']); ?>">
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="7">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No materials match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#materialsTable">Showing 1–<?= count($materials); ?> of <?= count($materials); ?> materials</p>
                    <nav aria-label="Materials pagination">
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

<!-- UI-only modal: Add Material -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <form data-materials-form>
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Material management</p>
                        <h2 class="modal-title fs-5" id="addMaterialModalLabel">Add New Material</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="materialId">Material ID</label>
                            <input class="form-control" id="materialId" name="material_id" type="number" min="1" placeholder="e.g. 7" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="materialName">Name</label>
                            <input class="form-control" id="materialName" name="name" type="text" placeholder="e.g. Denim Fabric" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="materialType">Type</label>
                            <input class="form-control" id="materialType" name="type" type="text" placeholder="e.g. Fabric" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="materialUnitOfMeasure">Unit of Measure</label>
                            <input class="form-control" id="materialUnitOfMeasure" name="unit_of_measure" type="text" placeholder="e.g. meters" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="materialUnitPrice">Unit Price</label>
                            <input class="form-control" id="materialUnitPrice" name="unit_price" type="number" min="0" step="0.01" placeholder="e.g. 650" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Material</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewMaterialModal" tabindex="-1" aria-labelledby="viewMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Material details</p>
                    <h2 class="modal-title fs-5" id="viewMaterialModalLabel"><span data-material-detail="name">Cotton Fabric</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="detail-grid">
                    <div><dt>Material ID</dt><dd data-material-detail="id">#1</dd></div>
                    <div><dt>Type</dt><dd data-material-detail="type">Fabric</dd></div>
                    <div><dt>Unit of Measure</dt><dd data-material-detail="unitOfMeasure">meters</dd></div>
                    <div><dt>Unit Price</dt><dd data-material-detail="unitPrice">৳450</dd></div>
                    <div class="detail-grid__wide"><dt>Primary Supplier</dt><dd data-material-detail="supplier">Square Textiles</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this material"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Material</button>
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