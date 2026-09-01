<?php
require_once __DIR__ . '/../config/auth.php';

garments_session_start_safe();
if (!garments_current_user()) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = 'Orders';
$activePage = 'orders';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$orderMetrics = [
    ['label' => 'Total Orders', 'value' => '0', 'detail' => 'Orders recorded for August 2026', 'icon' => 'bi-clipboard2-check', 'tone' => 'primary'],
    ['label' => 'Ordered Units', 'value' => '0', 'detail' => 'Across the linked Order Style records', 'icon' => 'bi-box-seam', 'tone' => 'indigo'],
    ['label' => 'Final Bill', 'value' => '৳0', 'detail' => 'Combined linked Costing final bills', 'icon' => 'bi-cash-stack', 'tone' => 'teal'],
    ['label' => 'Outstanding', 'value' => '৳0', 'detail' => 'Across partially paid orders', 'icon' => 'bi-credit-card', 'tone' => 'orange'],
];

$orders = [];
$conn = garments_db_connect();
if ($conn) {
    $metricRows = [
        'total_orders' => garments_db_fetch_one('SELECT COUNT(*) AS total FROM Orders'),
        'ordered_units' => garments_db_fetch_one('SELECT NVL(SUM(Quantity), 0) AS total FROM Rel_Order_OrderStyle'),
        'final_bill' => garments_db_fetch_one('SELECT NVL(SUM(Final_Bill), 0) AS total FROM Costing'),
        'outstanding' => garments_db_fetch_one('SELECT NVL(SUM(Remaining_Amount), 0) AS total FROM Payment WHERE Remaining_Amount > 0'),
    ];

    if ($metricRows['total_orders']) {
        $orderMetrics[0]['value'] = (string) (int) ($metricRows['total_orders']['TOTAL'] ?? 0);
    }
    if ($metricRows['ordered_units']) {
        $orderMetrics[1]['value'] = number_format((int) ($metricRows['ordered_units']['TOTAL'] ?? 0), 0, '.', ',');
    }
    if ($metricRows['final_bill']) {
        $orderMetrics[2]['value'] = '৳' . number_format((float) ($metricRows['final_bill']['TOTAL'] ?? 0), 2, '.', ',');
    }
    if ($metricRows['outstanding']) {
        $orderMetrics[3]['value'] = '৳' . number_format((float) ($metricRows['outstanding']['TOTAL'] ?? 0), 2, '.', ',');
    }

    $orderSql = "
        SELECT
            o.Order_ID AS id,
            b.Name AS buyer,
            LOWER(REPLACE(b.Name, ' ', '-')) AS buyer_key,
            o.Description,
            os.Style_Name || ' · ' || os.Color || ' / ' || os.Size_Value AS style,
            NVL(SUM(roos.Quantity), 0) AS quantity,
            TO_CHAR(o.Order_Date, 'DD Mon YYYY') AS order_date,
            TO_CHAR(o.Estimate_Delivery, 'DD Mon YYYY') AS delivery_date,
            NVL(c.Final_Bill, 0) AS final_bill,
            NVL(p.Paid_Amount, 0) AS paid_amount,
            NVL(p.Remaining_Amount, 0) AS remaining_amount,
            NVL(p.Payment_Method, 'N/A') AS payment_method,
            TO_CHAR(p.Payment_Date, 'DD Mon YYYY') AS payment_date,
            CASE WHEN NVL(p.Remaining_Amount, 0) = 0 THEN 'Paid' ELSE 'Partially Paid' END AS status
        FROM Orders o
        LEFT JOIN Rel_Buyer_Order rbo ON rbo.Order_ID = o.Order_ID
        LEFT JOIN Buyer b ON b.Buyer_ID = rbo.Buyer_ID
        LEFT JOIN Rel_Order_OrderStyle roos ON roos.Order_ID = o.Order_ID
        LEFT JOIN Order_Style os ON os.Order_ID = o.Order_ID AND os.Style_ID = roos.Style_ID
        LEFT JOIN Rel_Costing_Order rco ON rco.Order_ID = o.Order_ID
        LEFT JOIN Costing c ON c.Costing_ID = rco.Costing_ID
        LEFT JOIN Rel_Costing_Payment rcp ON rcp.Costing_ID = c.Costing_ID
        LEFT JOIN Payment p ON p.Payment_ID = rcp.Payment_ID
        GROUP BY
            o.Order_ID, b.Name, o.Description, os.Style_Name, os.Color, os.Size_Value,
            o.Order_Date, o.Estimate_Delivery, c.Final_Bill, p.Paid_Amount,
            p.Remaining_Amount, p.Payment_Method, p.Payment_Date
        ORDER BY o.Order_ID DESC
    ";

    $dbOrders = garments_db_fetch_all($orderSql);
    if (!empty($dbOrders)) {
        foreach ($dbOrders as $row) {
            $remaining = (float) ($row['REMAINING_AMOUNT'] ?? 0);
            $status = $remaining > 0 ? 'Partially Paid' : 'Paid';
            $statusKey = $remaining > 0 ? 'partial' : 'paid';
            $statusClass = $remaining > 0 ? 'warning' : 'success';

            $orders[] = [
                'id' => (string) ($row['ID'] ?? ''),
                'buyer' => (string) ($row['BUYER'] ?? 'N/A'),
                'buyerKey' => (string) ($row['BUYER_KEY'] ?? strtolower((string) ($row['BUYER'] ?? 'n-a'))),
                'description' => (string) ($row['DESCRIPTION'] ?? 'N/A'),
                'style' => (string) ($row['STYLE'] ?? 'N/A'),
                'quantity' => number_format((int) ($row['QUANTITY'] ?? 0), 0, '.', ','),
                'orderDate' => (string) ($row['ORDER_DATE'] ?? 'N/A'),
                'deliveryDate' => (string) ($row['DELIVERY_DATE'] ?? 'N/A'),
                'finalBill' => '৳' . number_format((float) ($row['FINAL_BILL'] ?? 0), 2, '.', ','),
                'paidAmount' => '৳' . number_format((float) ($row['PAID_AMOUNT'] ?? 0), 2, '.', ','),
                'remainingAmount' => '৳' . number_format((float) ($row['REMAINING_AMOUNT'] ?? 0), 2, '.', ','),
                'paymentMethod' => (string) ($row['PAYMENT_METHOD'] ?? 'N/A'),
                'paymentDate' => (string) ($row['PAYMENT_DATE'] ?? 'N/A'),
                'status' => $status,
                'statusKey' => $statusKey,
                'statusClass' => $statusClass,
            ];
        }
    }
}

if (empty($orders)) {
    $orders = [
        [
            'id' => '1', 'buyer' => 'ABC Fashion', 'buyerKey' => 'abc-fashion', 'description' => '1,000 Polo Shirts', 'style' => 'Polo Shirt · Blue / M', 'quantity' => '1,000', 'orderDate' => '01 Aug 2026', 'deliveryDate' => '15 Aug 2026', 'finalBill' => '৳500,000', 'paidAmount' => '৳300,000', 'remainingAmount' => '৳200,000', 'paymentMethod' => 'Bank Transfer', 'paymentDate' => '12 Aug 2026', 'status' => 'Partially Paid', 'statusKey' => 'partial', 'statusClass' => 'warning',
        ],
        [
            'id' => '2', 'buyer' => 'Global Wear', 'buyerKey' => 'global-wear', 'description' => '800 Hoodies', 'style' => 'Hoodie · Black / L', 'quantity' => '800', 'orderDate' => '02 Aug 2026', 'deliveryDate' => '18 Aug 2026', 'finalBill' => '৳620,000', 'paidAmount' => '৳620,000', 'remainingAmount' => '৳0', 'paymentMethod' => 'LC', 'paymentDate' => '13 Aug 2026', 'status' => 'Paid', 'statusKey' => 'paid', 'statusClass' => 'success',
        ],
        [
            'id' => '3', 'buyer' => 'Urban Style', 'buyerKey' => 'urban-style', 'description' => '1,500 T-Shirts', 'style' => 'T-Shirt · White / XL', 'quantity' => '1,500', 'orderDate' => '03 Aug 2026', 'deliveryDate' => '16 Aug 2026', 'finalBill' => '৳475,000', 'paidAmount' => '৳250,000', 'remainingAmount' => '৳225,000', 'paymentMethod' => 'Bank Transfer', 'paymentDate' => '14 Aug 2026', 'status' => 'Partially Paid', 'statusKey' => 'partial', 'statusClass' => 'warning',
        ],
        [
            'id' => '4', 'buyer' => 'Classic Apparel', 'buyerKey' => 'classic-apparel', 'description' => '500 Jackets', 'style' => 'Jacket · Navy / L', 'quantity' => '500', 'orderDate' => '04 Aug 2026', 'deliveryDate' => '20 Aug 2026', 'finalBill' => '৳710,000', 'paidAmount' => '৳500,000', 'remainingAmount' => '৳210,000', 'paymentMethod' => 'SWIFT', 'paymentDate' => '15 Aug 2026', 'status' => 'Partially Paid', 'statusKey' => 'partial', 'statusClass' => 'warning',
        ],
        [
            'id' => '5', 'buyer' => 'Elite Clothing', 'buyerKey' => 'elite-clothing', 'description' => '1,200 Sports Jerseys', 'style' => 'Sports Jersey · Red / M', 'quantity' => '1,200', 'orderDate' => '05 Aug 2026', 'deliveryDate' => '19 Aug 2026', 'finalBill' => '৳390,000', 'paidAmount' => '৳390,000', 'remainingAmount' => '৳0', 'paymentMethod' => 'Cash', 'paymentDate' => '16 Aug 2026', 'status' => 'Paid', 'statusKey' => 'paid', 'statusClass' => 'success',
        ],
        [
            'id' => '6', 'buyer' => 'Tokyo Fashion', 'buyerKey' => 'tokyo-fashion', 'description' => '700 Sweatshirts', 'style' => 'Sweatshirt · Grey / XL', 'quantity' => '700', 'orderDate' => '06 Aug 2026', 'deliveryDate' => '22 Aug 2026', 'finalBill' => '৳830,000', 'paidAmount' => '৳600,000', 'remainingAmount' => '৳230,000', 'paymentMethod' => 'LC', 'paymentDate' => '17 Aug 2026', 'status' => 'Partially Paid', 'statusKey' => 'partial', 'statusClass' => 'warning',
        ],
    ];
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="orders-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </nav>
                <h1 id="orders-heading">Orders</h1>
                <p>Track buyer orders, linked styles, delivery commitments, costing, and payment status.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Order</span>
            </button>
        </section>

        <section aria-label="Order overview">
            <div class="row g-3">
                <?php foreach ($orderMetrics as $metric): ?>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <article class="metric-card metric-card--<?= $escape($metric['tone']); ?>">
                            <div class="metric-card__topline">
                                <span class="metric-card__icon"><i class="bi <?= $escape($metric['icon']); ?>" aria-hidden="true"></i></span>
                                <span class="metric-card__trend"><i class="bi bi-arrow-up-right" aria-hidden="true"></i> August</span>
                            </div>
                            <p class="metric-card__value"><?= $escape($metric['value']); ?></p>
                            <h2 class="metric-card__label"><?= $escape($metric['label']); ?></h2>
                            <p class="metric-card__detail"><?= $escape($metric['detail']); ?></p>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="dashboard-section" aria-labelledby="orders-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Order register</p>
                        <h2 id="orders-table-heading">All Orders</h2>
                    </div>
                    <span class="card-period">August 2026</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by order, buyer, or description" aria-label="Search orders" data-table-search="#ordersTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter orders by payment status" data-table-filter="#ordersTable" data-filter-key="status">
                            <option value="all">All payment statuses</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partially Paid</option>
                        </select>
                        <select class="form-select" aria-label="Filter orders by buyer" data-table-filter="#ordersTable" data-filter-key="buyer">
                            <option value="all">All buyers</option>
                            <?php foreach ($orders as $order): ?>
                                <option value="<?= $escape($order['buyerKey']); ?>"><?= $escape($order['buyer']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table orders-table align-middle mb-0" id="ordersTable">
                        <caption class="visually-hidden">Static garments order data with buyer, style, quantity, delivery, costing, and payment details</caption>
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Buyer</th>
                                <th scope="col">Description</th>
                                <th scope="col">Order Style</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Estimated Delivery</th>
                                <th scope="col">Final Bill</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr data-search-row data-status="<?= $escape($order['statusKey']); ?>" data-buyer="<?= $escape($order['buyerKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($order['id']); ?></span></td>
                                    <td><strong><?= $escape($order['buyer']); ?></strong></td>
                                    <td><?= $escape($order['description']); ?></td>
                                    <td><span class="table-row-meta"><?= $escape($order['style']); ?></span></td>
                                    <td><?= $escape($order['quantity']); ?></td>
                                    <td><?= $escape($order['orderDate']); ?></td>
                                    <td><?= $escape($order['deliveryDate']); ?></td>
                                    <td><strong><?= $escape($order['finalBill']); ?></strong></td>
                                    <td><span class="status-badge status-badge--<?= $escape($order['statusClass']); ?>"><?= $escape($order['status']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View Order #<?= $escape($order['id']); ?>"
                                                aria-label="View Order #<?= $escape($order['id']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewOrderModal"
                                                data-order-id="#<?= $escape($order['id']); ?>"
                                                data-order-buyer="<?= $escape($order['buyer']); ?>"
                                                data-order-description="<?= $escape($order['description']); ?>"
                                                data-order-style="<?= $escape($order['style']); ?>"
                                                data-order-quantity="<?= $escape($order['quantity']); ?>"
                                                data-order-date="<?= $escape($order['orderDate']); ?>"
                                                data-order-delivery="<?= $escape($order['deliveryDate']); ?>"
                                                data-order-final-bill="<?= $escape($order['finalBill']); ?>"
                                                data-order-paid="<?= $escape($order['paidAmount']); ?>"
                                                data-order-remaining="<?= $escape($order['remainingAmount']); ?>"
                                                data-order-payment-method="<?= $escape($order['paymentMethod']); ?>"
                                                data-order-payment-date="<?= $escape($order['paymentDate']); ?>"
                                                data-order-status="<?= $escape($order['status']); ?>"
                                                data-order-status-class="<?= $escape($order['statusClass']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit Order #<?= $escape($order['id']); ?>" aria-label="Edit Order #<?= $escape($order['id']); ?>" data-prototype-action="Edit Order #<?= $escape($order['id']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button table-action-button--danger" type="button" title="Delete Order #<?= $escape($order['id']); ?>" aria-label="Delete Order #<?= $escape($order['id']); ?>" data-prototype-action="Delete Order #<?= $escape($order['id']); ?>">
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="10">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No orders match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#ordersTable">Showing 1–6 of 6 orders</p>
                    <nav aria-label="Orders pagination">
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

<!-- UI-only modal: Order attributes plus its Buyer and Order Style relationships. -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content app-modal">
<form data-orders-form data-backend-resource="order">
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Order register</p>
                        <h2 class="modal-title fs-5" id="addOrderModalLabel">Add New Order</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="orderId">Order ID</label>
                            <input class="form-control" id="orderId" name="order_id" type="number" min="1" placeholder="e.g. 7" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="orderBuyer">Buyer</label>
                            <select class="form-select" id="orderBuyer" name="buyer_id" required>
                                <option value="" selected disabled>Select a buyer</option>
                                <option value="1">ABC Fashion</option>
                                <option value="2">Global Wear</option>
                                <option value="3">Urban Style</option>
                                <option value="4">Classic Apparel</option>
                                <option value="5">Elite Clothing</option>
                                <option value="6">Tokyo Fashion</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="orderDescription">Description</label>
                            <input class="form-control" id="orderDescription" name="description" type="text" placeholder="e.g. 900 Cotton Polo Shirts" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="orderDate">Order Date</label>
                            <input class="form-control" id="orderDate" name="order_date" type="date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="deliveryDate">Estimated Delivery</label>
                            <input class="form-control" id="deliveryDate" name="estimate_delivery" type="date" required>
                        </div>
                    </div>

                    <div class="modal-subsection">
                        <div>
                            <h3>Initial Order Style</h3>
                            <p>Style details belong to the Order Style record; quantity belongs to its identifying relationship with this order.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label" for="styleId">Style ID</label>
                                <input class="form-control" id="styleId" name="style_id" type="number" min="1" placeholder="e.g. 7">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="styleName">Style Name</label>
                                <input class="form-control" id="styleName" name="style_name" type="text" placeholder="e.g. Polo Shirt">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="styleColor">Color</label>
                                <input class="form-control" id="styleColor" name="color" type="text" placeholder="Navy">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label" for="styleSize">Size</label>
                                <input class="form-control" id="styleSize" name="size" type="text" placeholder="M">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="styleQuantity">Quantity</label>
                                <input class="form-control" id="styleQuantity" name="quantity" type="number" min="1" placeholder="900">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Order details</p>
                    <h2 class="modal-title fs-5" id="viewOrderModalLabel">Order <span data-order-detail="id">#1</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Payment status from linked Payment</span>
                    <span class="status-badge status-badge--warning" data-order-detail="status">Partially Paid</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Buyer</dt><dd data-order-detail="buyer">ABC Fashion</dd></div>
                    <div><dt>Final Bill</dt><dd data-order-detail="finalBill">৳500,000</dd></div>
                    <div class="detail-grid__wide"><dt>Description</dt><dd data-order-detail="description">1,000 Polo Shirts</dd></div>
                    <div><dt>Order Style</dt><dd data-order-detail="style">Polo Shirt · Blue / M</dd></div>
                    <div><dt>Quantity</dt><dd data-order-detail="quantity">1,000</dd></div>
                    <div><dt>Order Date</dt><dd data-order-detail="date">01 Aug 2026</dd></div>
                    <div><dt>Estimated Delivery</dt><dd data-order-detail="delivery">15 Aug 2026</dd></div>
                    <div><dt>Paid Amount</dt><dd data-order-detail="paid">৳300,000</dd></div>
                    <div><dt>Remaining Amount</dt><dd data-order-detail="remaining">৳200,000</dd></div>
                    <div><dt>Payment Method</dt><dd data-order-detail="paymentMethod">Bank Transfer</dd></div>
                    <div><dt>Payment Date</dt><dd data-order-detail="paymentDate">12 Aug 2026</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this order"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Order</button>
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
