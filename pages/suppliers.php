<?php
/**
 * Suppliers frontend prototype.
 * Static records reflect Supplier, Supplier_Contact, and the ternary
 * Supplier–BOM–Material relationship from the supplied schema.
 */
$pageTitle = 'Suppliers';
$activePage = 'suppliers';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$supplierMetrics = [
    ['label' => 'Total Suppliers', 'value' => '6', 'detail' => 'Supplier profiles in the materials network', 'icon' => 'bi-truck-flatbed', 'tone' => 'primary'],
    ['label' => 'Contact Numbers', 'value' => '6', 'detail' => 'Registered multivalued supplier contacts', 'icon' => 'bi-telephone', 'tone' => 'indigo'],
    ['label' => 'Material Links', 'value' => '6', 'detail' => 'Supplier–BOM–Material relationship records', 'icon' => 'bi-diagram-3', 'tone' => 'teal'],
    ['label' => 'Longest Lead Time', 'value' => '10 days', 'detail' => 'Polyester fabric sourcing for Winter Hoodie', 'icon' => 'bi-hourglass-split', 'tone' => 'orange'],
];

$suppliers = [
    [
        'id' => '1', 'name' => 'Square Textiles', 'initials' => 'ST', 'contact' => '01710 000001', 'email' => 'square@supplier.com', 'address' => 'Gazipur',
        'material' => 'Cotton Fabric', 'materialType' => 'Fabric', 'materialKey' => 'fabric', 'bom' => 'Cotton Polo Shirt', 'quantity' => '1,000 meters', 'timeRequired' => '7 days', 'leadTimeKey' => 'extended',
    ],
    [
        'id' => '2', 'name' => 'DBL Fabrics', 'initials' => 'DF', 'contact' => '01710 000002', 'email' => 'dbl@supplier.com', 'address' => 'Narayanganj',
        'material' => 'Polyester Fabric', 'materialType' => 'Fabric', 'materialKey' => 'fabric', 'bom' => 'Winter Hoodie', 'quantity' => '800 meters', 'timeRequired' => '10 days', 'leadTimeKey' => 'extended',
    ],
    [
        'id' => '3', 'name' => 'ABC Accessories', 'initials' => 'AA', 'contact' => '01710 000003', 'email' => 'abc@supplier.com', 'address' => 'Dhaka',
        'material' => 'Sewing Thread', 'materialType' => 'Thread', 'materialKey' => 'thread', 'bom' => 'Basic T-Shirt', 'quantity' => '150 cones', 'timeRequired' => '5 days', 'leadTimeKey' => 'standard',
    ],
    [
        'id' => '4', 'name' => 'Cotton World', 'initials' => 'CW', 'contact' => '01710 000004', 'email' => 'cotton@supplier.com', 'address' => 'Chattogram',
        'material' => 'Buttons', 'materialType' => 'Accessories', 'materialKey' => 'accessories', 'bom' => 'Denim Jacket', 'quantity' => '500 pieces', 'timeRequired' => '3 days', 'leadTimeKey' => 'standard',
    ],
    [
        'id' => '5', 'name' => 'Fashion Source', 'initials' => 'FS', 'contact' => '01710 000005', 'email' => 'fashion@supplier.com', 'address' => 'Cumilla',
        'material' => 'Zipper', 'materialType' => 'Accessories', 'materialKey' => 'accessories', 'bom' => 'Sports Jersey', 'quantity' => '1,200 pieces', 'timeRequired' => '4 days', 'leadTimeKey' => 'standard',
    ],
    [
        'id' => '6', 'name' => 'Global Textile Ltd.', 'initials' => 'GT', 'contact' => '01710 000006', 'email' => 'global@supplier.com', 'address' => 'Savar',
        'material' => 'Neck Label', 'materialType' => 'Label', 'materialKey' => 'label', 'bom' => 'Premium Sweatshirt', 'quantity' => '700 pieces', 'timeRequired' => '6 days', 'leadTimeKey' => 'extended',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="suppliers-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
                    </ol>
                </nav>
                <h1 id="suppliers-heading">Suppliers</h1>
                <p>Monitor supplier profiles and their material requirements across the linked bills of materials.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Supplier</span>
            </button>
        </section>

        <section aria-label="Supplier overview">
            <div class="row g-3">
                <?php foreach ($supplierMetrics as $metric): ?>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-card--<?= $escape($metric['tone']); ?>">
                            <div class="metric-card__topline">
                                <span class="metric-card__icon"><i class="bi <?= $escape($metric['icon']); ?>" aria-hidden="true"></i></span>
                                <span class="metric-card__trend"><i class="bi bi-link-45deg" aria-hidden="true"></i> Mapped</span>
                            </div>
                            <p class="metric-card__value"><?= $escape($metric['value']); ?></p>
                            <h2 class="metric-card__label"><?= $escape($metric['label']); ?></h2>
                            <p class="metric-card__detail"><?= $escape($metric['detail']); ?></p>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="dashboard-section" aria-labelledby="suppliers-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Supplier directory</p>
                        <h2 id="suppliers-table-heading">All Suppliers</h2>
                    </div>
                    <span class="card-period">6 material partners</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by supplier, material, BOM, or location" aria-label="Search suppliers" data-table-search="#suppliersTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter suppliers by material type" data-table-filter="#suppliersTable" data-filter-key="materialType">
                            <option value="all">All material types</option>
                            <option value="fabric">Fabric</option>
                            <option value="thread">Thread</option>
                            <option value="accessories">Accessories</option>
                            <option value="label">Label</option>
                        </select>
                        <select class="form-select" aria-label="Filter suppliers by lead time" data-table-filter="#suppliersTable" data-filter-key="leadTime">
                            <option value="all">All lead times</option>
                            <option value="standard">Up to 5 days</option>
                            <option value="extended">6 days or more</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table suppliers-table align-middle mb-0" id="suppliersTable" data-table-label="suppliers">
                        <caption class="visually-hidden">Static supplier profiles and Supplier–BOM–Material relationship details</caption>
                        <thead>
                            <tr>
                                <th scope="col">Supplier ID</th>
                                <th scope="col">Supplier</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Email</th>
                                <th scope="col">Address</th>
                                <th scope="col">Material</th>
                                <th scope="col">BOM</th>
                                <th scope="col">Required Quantity</th>
                                <th scope="col">Time Required</th>
                                <th scope="col">Link Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($suppliers as $supplier): ?>
                                <tr data-search-row data-material-type="<?= $escape($supplier['materialKey']); ?>" data-lead-time="<?= $escape($supplier['leadTimeKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($supplier['id']); ?></span></td>
                                    <td>
                                        <span class="supplier-identity">
                                            <span class="supplier-identity__avatar"><?= $escape($supplier['initials']); ?></span>
                                            <strong><?= $escape($supplier['name']); ?></strong>
                                        </span>
                                    </td>
                                    <td><span class="contact-value"><i class="bi bi-telephone" aria-hidden="true"></i><?= $escape($supplier['contact']); ?></span></td>
                                    <td><a class="table-email" href="mailto:<?= $escape($supplier['email']); ?>"><?= $escape($supplier['email']); ?></a></td>
                                    <td><span class="table-row-meta"><?= $escape($supplier['address']); ?></span></td>
                                    <td>
                                        <span class="material-link">
                                            <i class="bi bi-box-seam" aria-hidden="true"></i>
                                            <span><strong><?= $escape($supplier['material']); ?></strong><small><?= $escape($supplier['materialType']); ?></small></span>
                                        </span>
                                    </td>
                                    <td><span class="table-row-meta"><?= $escape($supplier['bom']); ?></span></td>
                                    <td><strong><?= $escape($supplier['quantity']); ?></strong></td>
                                    <td><span class="lead-time-chip"><i class="bi bi-clock" aria-hidden="true"></i><?= $escape($supplier['timeRequired']); ?></span></td>
                                    <td><span class="status-badge status-badge--success">Mapped</span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View <?= $escape($supplier['name']); ?>"
                                                aria-label="View <?= $escape($supplier['name']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewSupplierModal"
                                                data-supplier-id="#<?= $escape($supplier['id']); ?>"
                                                data-supplier-name="<?= $escape($supplier['name']); ?>"
                                                data-supplier-initials="<?= $escape($supplier['initials']); ?>"
                                                data-supplier-contact="<?= $escape($supplier['contact']); ?>"
                                                data-supplier-email="<?= $escape($supplier['email']); ?>"
                                                data-supplier-address="<?= $escape($supplier['address']); ?>"
                                                data-supplier-material="<?= $escape($supplier['material']); ?>"
                                                data-supplier-material-type="<?= $escape($supplier['materialType']); ?>"
                                                data-supplier-bom="<?= $escape($supplier['bom']); ?>"
                                                data-supplier-quantity="<?= $escape($supplier['quantity']); ?>"
                                                data-supplier-time-required="<?= $escape($supplier['timeRequired']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit <?= $escape($supplier['name']); ?>" aria-label="Edit <?= $escape($supplier['name']); ?>" data-prototype-action="Edit <?= $escape($supplier['name']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button table-action-button--danger" type="button" title="Delete <?= $escape($supplier['name']); ?>" aria-label="Delete <?= $escape($supplier['name']); ?>" data-prototype-action="Delete <?= $escape($supplier['name']); ?>">
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="11">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No suppliers match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#suppliersTable">Showing 1–6 of 6 suppliers</p>
                    <nav aria-label="Suppliers pagination">
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

<!-- UI-only Supplier and Supplier_Contact form. -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <form data-suppliers-form>
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Supplier profile</p>
                        <h2 class="modal-title fs-5" id="addSupplierModalLabel">Add Supplier</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This form is a frontend-only preview and will not save supplier information.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="supplierId">Supplier ID</label>
                            <input class="form-control" id="supplierId" name="supplier_id" type="number" min="1" placeholder="e.g. 7" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="supplierName">Name</label>
                            <input class="form-control" id="supplierName" name="name" type="text" placeholder="e.g. Meridian Textiles" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="supplierEmail">Email</label>
                            <input class="form-control" id="supplierEmail" name="email" type="email" placeholder="supplier@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="supplierAddress">Address</label>
                            <input class="form-control" id="supplierAddress" name="address" type="text" placeholder="City or district" required>
                        </div>
                    </div>

                    <div class="modal-subsection">
                        <div>
                            <h3>Contact Numbers</h3>
                            <p>Contact Number is a multivalued Supplier attribute.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="supplierContactPrimary">Primary Contact Number</label>
                                <input class="form-control" id="supplierContactPrimary" name="contact_number_primary" type="tel" placeholder="e.g. 01710 000007" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="supplierContactAdditional">Additional Contact Number</label>
                                <input class="form-control" id="supplierContactAdditional" name="contact_number_additional" type="tel" placeholder="Optional">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal populated from a selected static Supplier record. -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1" aria-labelledby="viewSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Supplier details</p>
                    <h2 class="modal-title fs-5" id="viewSupplierModalLabel"><span data-supplier-detail="name">Square Textiles</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="supplier-detail-hero">
                    <span class="supplier-detail-hero__avatar" data-supplier-detail="initials">ST</span>
                    <div>
                        <strong>Supplier ID <span data-supplier-detail="id">#1</span></strong>
                        <span data-supplier-detail="address">Gazipur</span>
                    </div>
                    <span class="status-badge status-badge--success">Mapped</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Contact Number</dt><dd data-supplier-detail="contact">01710 000001</dd></div>
                    <div><dt>Email</dt><dd data-supplier-detail="email">square@supplier.com</dd></div>
                    <div><dt>Material</dt><dd data-supplier-detail="material">Cotton Fabric</dd></div>
                    <div><dt>Material Type</dt><dd data-supplier-detail="materialType">Fabric</dd></div>
                    <div class="detail-grid__wide"><dt>Linked BOM</dt><dd data-supplier-detail="bom">Cotton Polo Shirt</dd></div>
                    <div><dt>Required Quantity</dt><dd data-supplier-detail="quantity">1,000 meters</dd></div>
                    <div><dt>Time Required</dt><dd data-supplier-detail="timeRequired">7 days</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this supplier"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Supplier</button>
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
