<?php
/**
 * Order Styles frontend prototype.
 * Each record follows the weak Order_Style entity and its identifying
 * Rel_Order_OrderStyle relationship from the supplied schema.
 */
$pageTitle = 'Order Styles';
$activePage = 'order-styles';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$styleMetrics = [
    ['label' => 'Total Styles', 'value' => '6', 'detail' => 'One linked style record for each order', 'icon' => 'bi-palette2', 'tone' => 'primary'],
    ['label' => 'Linked Orders', 'value' => '6', 'detail' => 'All styles have total participation', 'icon' => 'bi-link-45deg', 'tone' => 'indigo'],
    ['label' => 'Units Covered', 'value' => '5,700', 'detail' => 'Quantity from Order–Style relationships', 'icon' => 'bi-box-seam', 'tone' => 'teal'],
    ['label' => 'Color Variants', 'value' => '6', 'detail' => 'Blue, Black, White, Navy, Red, and Grey', 'icon' => 'bi-palette-fill', 'tone' => 'purple'],
];

$orderStyles = [
    ['orderId' => '1', 'styleId' => '1', 'styleName' => 'Polo Shirt', 'color' => 'Blue', 'colorKey' => 'blue', 'size' => 'M', 'quantity' => '1,000', 'orderDescription' => '1,000 Polo Shirts'],
    ['orderId' => '2', 'styleId' => '2', 'styleName' => 'Hoodie', 'color' => 'Black', 'colorKey' => 'black', 'size' => 'L', 'quantity' => '800', 'orderDescription' => '800 Hoodies'],
    ['orderId' => '3', 'styleId' => '3', 'styleName' => 'T-Shirt', 'color' => 'White', 'colorKey' => 'white', 'size' => 'XL', 'quantity' => '1,500', 'orderDescription' => '1,500 T-Shirts'],
    ['orderId' => '4', 'styleId' => '4', 'styleName' => 'Jacket', 'color' => 'Navy', 'colorKey' => 'navy', 'size' => 'L', 'quantity' => '500', 'orderDescription' => '500 Jackets'],
    ['orderId' => '5', 'styleId' => '5', 'styleName' => 'Sports Jersey', 'color' => 'Red', 'colorKey' => 'red', 'size' => 'M', 'quantity' => '1,200', 'orderDescription' => '1,200 Sports Jerseys'],
    ['orderId' => '6', 'styleId' => '6', 'styleName' => 'Sweatshirt', 'color' => 'Grey', 'colorKey' => 'grey', 'size' => 'XL', 'quantity' => '700', 'orderDescription' => '700 Sweatshirts'],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="order-styles-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order Styles</li>
                    </ol>
                </nav>
                <h1 id="order-styles-heading">Order Styles</h1>
                <p>Manage the style, color, size, and quantity records attached to each buyer order.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addOrderStyleModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Style</span>
            </button>
        </section>

        <section aria-label="Order style overview">
            <div class="row g-3">
                <?php foreach ($styleMetrics as $metric): ?>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-card--<?= $escape($metric['tone']); ?>">
                            <div class="metric-card__topline">
                                <span class="metric-card__icon"><i class="bi <?= $escape($metric['icon']); ?>" aria-hidden="true"></i></span>
                                <span class="metric-card__trend"><i class="bi bi-link-45deg" aria-hidden="true"></i> Linked</span>
                            </div>
                            <p class="metric-card__value"><?= $escape($metric['value']); ?></p>
                            <h2 class="metric-card__label"><?= $escape($metric['label']); ?></h2>
                            <p class="metric-card__detail"><?= $escape($metric['detail']); ?></p>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="dashboard-section" aria-labelledby="order-styles-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Weak entity records</p>
                        <h2 id="order-styles-table-heading">All Order Styles</h2>
                    </div>
                    <span class="card-period">6 linked records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by style, color, or linked order" aria-label="Search order styles" data-table-search="#orderStylesTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter styles by size" data-table-filter="#orderStylesTable" data-filter-key="size">
                            <option value="all">All sizes</option>
                            <option value="m">M</option>
                            <option value="l">L</option>
                            <option value="xl">XL</option>
                        </select>
                        <select class="form-select" aria-label="Filter styles by color" data-table-filter="#orderStylesTable" data-filter-key="color">
                            <option value="all">All colors</option>
                            <option value="blue">Blue</option>
                            <option value="black">Black</option>
                            <option value="white">White</option>
                            <option value="navy">Navy</option>
                            <option value="red">Red</option>
                            <option value="grey">Grey</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table styles-table align-middle mb-0" id="orderStylesTable" data-table-label="order styles">
                        <caption class="visually-hidden">Static Order Style records linked to their parent orders</caption>
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Style ID</th>
                                <th scope="col">Style Name</th>
                                <th scope="col">Color</th>
                                <th scope="col">Size</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Linked Order</th>
                                <th scope="col">Link Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderStyles as $style): ?>
                                <tr data-search-row data-size="<?= $escape(strtolower($style['size'])); ?>" data-color="<?= $escape($style['colorKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($style['orderId']); ?></span></td>
                                    <td><span class="table-id">#<?= $escape($style['styleId']); ?></span></td>
                                    <td><strong><?= $escape($style['styleName']); ?></strong></td>
                                    <td>
                                        <span class="style-color">
                                            <span class="style-swatch style-swatch--<?= $escape($style['colorKey']); ?>" aria-hidden="true"></span>
                                            <?= $escape($style['color']); ?>
                                        </span>
                                    </td>
                                    <td><span class="size-chip"><?= $escape($style['size']); ?></span></td>
                                    <td><strong><?= $escape($style['quantity']); ?></strong></td>
                                    <td><span class="table-row-meta">#<?= $escape($style['orderId']); ?> · <?= $escape($style['orderDescription']); ?></span></td>
                                    <td><span class="status-badge status-badge--success">Linked</span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View Style #<?= $escape($style['styleId']); ?>"
                                                aria-label="View Style #<?= $escape($style['styleId']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewOrderStyleModal"
                                                data-style-order-id="#<?= $escape($style['orderId']); ?>"
                                                data-style-id="#<?= $escape($style['styleId']); ?>"
                                                data-style-name="<?= $escape($style['styleName']); ?>"
                                                data-style-color="<?= $escape($style['color']); ?>"
                                                data-style-color-class="<?= $escape($style['colorKey']); ?>"
                                                data-style-size="<?= $escape($style['size']); ?>"
                                                data-style-quantity="<?= $escape($style['quantity']); ?>"
                                                data-style-order-description="<?= $escape($style['orderDescription']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit Style #<?= $escape($style['styleId']); ?>" aria-label="Edit Style #<?= $escape($style['styleId']); ?>" data-prototype-action="Edit Style #<?= $escape($style['styleId']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button table-action-button--danger" type="button" title="Delete Style #<?= $escape($style['styleId']); ?>" aria-label="Delete Style #<?= $escape($style['styleId']); ?>" data-prototype-action="Delete Style #<?= $escape($style['styleId']); ?>">
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="9">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No order styles match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#orderStylesTable">Showing 1–6 of 6 order styles</p>
                    <nav aria-label="Order styles pagination">
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

<!-- UI-only form for the Order_Style entity and its identifying relationship. -->
<div class="modal fade" id="addOrderStyleModal" tabindex="-1" aria-labelledby="addOrderStyleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <form data-order-styles-form>
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Weak entity record</p>
                        <h2 class="modal-title fs-5" id="addOrderStyleModalLabel">Add Order Style</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This is a frontend-only form. It does not create or alter database records.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="styleOrderId">Order ID</label>
                            <select class="form-select" id="styleOrderId" name="order_id" required>
                                <option value="" selected disabled>Select the parent order</option>
                                <option value="1">#1 · 1,000 Polo Shirts</option>
                                <option value="2">#2 · 800 Hoodies</option>
                                <option value="3">#3 · 1,500 T-Shirts</option>
                                <option value="4">#4 · 500 Jackets</option>
                                <option value="5">#5 · 1,200 Sports Jerseys</option>
                                <option value="6">#6 · 700 Sweatshirts</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="newStyleId">Style ID</label>
                            <input class="form-control" id="newStyleId" name="style_id" type="number" min="1" placeholder="e.g. 7" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="newStyleName">Style Name</label>
                            <input class="form-control" id="newStyleName" name="style_name" type="text" placeholder="e.g. Cotton Polo Shirt" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="newStyleColor">Color</label>
                            <input class="form-control" id="newStyleColor" name="color" type="text" placeholder="e.g. Navy" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="newStyleSize">Size</label>
                            <input class="form-control" id="newStyleSize" name="size" type="text" placeholder="e.g. M" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="newStyleQuantity">Quantity</label>
                            <input class="form-control" id="newStyleQuantity" name="quantity" type="number" min="1" placeholder="e.g. 900" required>
                            <div class="form-text">Quantity is stored on the Order–Style relationship.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Style</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal populated from the selected static style record. -->
<div class="modal fade" id="viewOrderStyleModal" tabindex="-1" aria-labelledby="viewOrderStyleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Order Style details</p>
                    <h2 class="modal-title fs-5" id="viewOrderStyleModalLabel"><span data-style-detail="name">Polo Shirt</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="style-detail-preview">
                    <span class="style-detail-swatch style-swatch--blue" data-style-detail-swatch aria-hidden="true"></span>
                    <div>
                        <span data-style-detail="color">Blue</span>
                        <strong>Style <span data-style-detail="id">#1</span></strong>
                    </div>
                    <span class="size-chip" data-style-detail="size">M</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Parent Order ID</dt><dd data-style-detail="orderId">#1</dd></div>
                    <div><dt>Style ID</dt><dd data-style-detail="id">#1</dd></div>
                    <div class="detail-grid__wide"><dt>Linked Order</dt><dd data-style-detail="orderDescription">1,000 Polo Shirts</dd></div>
                    <div><dt>Quantity</dt><dd data-style-detail="quantity">1,000</dd></div>
                    <div><dt>Relationship Status</dt><dd><span class="status-badge status-badge--success">Linked</span></dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this order style"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Style</button>
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
