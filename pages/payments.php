<?php
/**
 * Payments frontend prototype.
 * Static records are based on the Payment entity and its relationships
 * with Buyer, Costing, and Accounts from the supplied schema.
 */
$pageTitle = 'Payments';
$activePage = 'payments';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$paymentMetrics = [
    ['label' => 'Total Payments', 'value' => '6', 'detail' => 'Payment records logged for August', 'icon' => 'bi-receipt', 'tone' => 'primary'],
    ['label' => 'Total Paid', 'value' => '৳2.68M', 'detail' => 'Sum of all paid amounts', 'icon' => 'bi-cash-stack', 'tone' => 'success'],
    ['label' => 'Total Remaining', 'value' => '৳865K', 'detail' => 'Across four partially paid orders', 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
    ['label' => 'Payment Methods', 'value' => '4', 'detail' => 'Bank Transfer, LC, SWIFT, and Cash', 'icon' => 'bi-credit-card-2-front', 'tone' => 'indigo'],
];

$payments = [
    [
        'id' => '1', 'orderId' => '1', 'buyerName' => 'ABC Fashion', 'buyerKey' => 'abc-fashion',
        'totalAmount' => '500000', 'paidAmount' => '300000', 'remainingAmount' => '200000',
        'paymentMethod' => 'Bank Transfer', 'methodKey' => 'bank-transfer', 'date' => '12 Aug 2026',
        'status' => 'Partially Paid', 'statusKey' => 'partial', 'statusClass' => 'warning',
    ],
    [
        'id' => '2', 'orderId' => '2', 'buyerName' => 'Global Wear', 'buyerKey' => 'global-wear',
        'totalAmount' => '620000', 'paidAmount' => '620000', 'remainingAmount' => '0',
        'paymentMethod' => 'LC', 'methodKey' => 'lc', 'date' => '13 Aug 2026',
        'status' => 'Paid', 'statusKey' => 'paid', 'statusClass' => 'success',
    ],
    [
        'id' => '3', 'orderId' => '3', 'buyerName' => 'Urban Style', 'buyerKey' => 'urban-style',
        'totalAmount' => '475000', 'paidAmount' => '250000', 'remainingAmount' => '225000',
        'paymentMethod' => 'Bank Transfer', 'methodKey' => 'bank-transfer', 'date' => '14 Aug 2026',
        'status' => 'Partially Paid', 'statusKey' => 'partial', 'statusClass' => 'warning',
    ],
    [
        'id' => '4', 'orderId' => '4', 'buyerName' => 'Classic Apparel', 'buyerKey' => 'classic-apparel',
        'totalAmount' => '710000', 'paidAmount' => '500000', 'remainingAmount' => '210000',
        'paymentMethod' => 'SWIFT', 'methodKey' => 'swift', 'date' => '15 Aug 2026',
        'status' => 'Partially Paid', 'statusKey' => 'partial', 'statusClass' => 'warning',
    ],
    [
        'id' => '5', 'orderId' => '5', 'buyerName' => 'Elite Clothing', 'buyerKey' => 'elite-clothing',
        'totalAmount' => '390000', 'paidAmount' => '390000', 'remainingAmount' => '0',
        'paymentMethod' => 'Cash', 'methodKey' => 'cash', 'date' => '16 Aug 2026',
        'status' => 'Paid', 'statusKey' => 'paid', 'statusClass' => 'success',
    ],
    [
        'id' => '6', 'orderId' => '6', 'buyerName' => 'Tokyo Fashion', 'buyerKey' => 'tokyo-fashion',
        'totalAmount' => '830000', 'paidAmount' => '600000', 'remainingAmount' => '230000',
        'paymentMethod' => 'LC', 'methodKey' => 'lc', 'date' => '17 Aug 2026',
        'status' => 'Partially Paid', 'statusKey' => 'partial', 'statusClass' => 'warning',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="payments-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payments</li>
                    </ol>
                </nav>
                <h1 id="payments-heading">Payments</h1>
                <p>Manage all payment records, track amounts, and link to accounts.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Payment</span>
            </button>
        </section>

        <section aria-label="Payment overview">
            <div class="row g-3">
                <?php foreach ($paymentMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="payments-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Payment ledger</p>
                        <h2 id="payments-table-heading">All Payments</h2>
                    </div>
                    <span class="card-period">6 payment records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by order ID or buyer" aria-label="Search payments" data-table-search="#paymentsTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter payments by status" data-table-filter="#paymentsTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partially Paid</option>
                        </select>
                        <select class="form-select" aria-label="Filter payments by method" data-table-filter="#paymentsTable" data-filter-key="method">
                            <option value="all">All Methods</option>
                            <option value="bank-transfer">Bank Transfer</option>
                            <option value="lc">LC</option>
                            <option value="swift">SWIFT</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table payments-table align-middle mb-0" id="paymentsTable" data-table-label="payments">
                        <caption class="visually-hidden">Static payment data with amounts, method, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Payment ID</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Buyer</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col">Paid Amount</th>
                                <th scope="col">Remaining</th>
                                <th scope="col">Method</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr data-search-row data-status="<?= $escape($payment['statusKey']); ?>" data-method="<?= $escape($payment['methodKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($payment['id']); ?></span></td>
                                    <td><span class="table-row-meta">#<?= $escape($payment['orderId']); ?></span></td>
                                    <td><strong><?= $escape($payment['buyerName']); ?></strong></td>
                                    <td>৳<?= $escape(number_format((float) $payment['totalAmount'])); ?></td>
                                    <td><strong class="text-success">৳<?= $escape(number_format((float) $payment['paidAmount'])); ?></strong></td>
                                    <td><strong class="text-danger">৳<?= $escape(number_format((float) $payment['remainingAmount'])); ?></strong></td>
                                    <td><span class="table-row-meta"><?= $escape($payment['paymentMethod']); ?></span></td>
                                    <td><?= $escape($payment['date']); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($payment['statusClass']); ?>"><?= $escape($payment['status']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View Payment #<?= $escape($payment['id']); ?>"
                                                aria-label="View Payment #<?= $escape($payment['id']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewPaymentModal"
                                                data-payment-id="#<?= $escape($payment['id']); ?>"
                                                data-payment-order-id="#<?= $escape($payment['orderId']); ?>"
                                                data-payment-buyer-name="<?= $escape($payment['buyerName']); ?>"
                                                data-payment-total-amount="৳<?= $escape(number_format((float) $payment['totalAmount'])); ?>"
                                                data-payment-paid-amount="৳<?= $escape(number_format((float) $payment['paidAmount'])); ?>"
                                                data-payment-remaining-amount="৳<?= $escape(number_format((float) $payment['remainingAmount'])); ?>"
                                                data-payment-method="<?= $escape($payment['paymentMethod']); ?>"
                                                data-payment-date="<?= $escape($payment['date']); ?>"
                                                data-payment-status="<?= $escape($payment['status']); ?>"
                                                data-payment-status-class="<?= $escape($payment['statusClass']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit Payment #<?= $escape($payment['id']); ?>" aria-label="Edit Payment #<?= $escape($payment['id']); ?>" data-prototype-action="Edit Payment #<?= $escape($payment['id']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="10">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No payments match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#paymentsTable">Showing 1–<?= count($payments); ?> of <?= count($payments); ?> payments</p>
                    <nav aria-label="Payments pagination">
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

<!-- UI-only modal: Add Payment -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <form data-payments-form>
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Financials</p>
                        <h2 class="modal-title fs-5" id="addPaymentModalLabel">Add New Payment Record</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="paymentOrderId">Order ID</label>
                            <input class="form-control" id="paymentOrderId" name="order_id" type="number" min="1" placeholder="e.g. 7" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="paymentDate">Payment Date</label>
                            <input class="form-control" id="paymentDate" name="payment_date" type="date" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="paymentTotal">Total Amount</label>
                            <input class="form-control" id="paymentTotal" name="total_amount" type="number" min="0" placeholder="e.g. 500000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="paymentPaid">Paid Amount</label>
                            <input class="form-control" id="paymentPaid" name="paid_amount" type="number" min="0" placeholder="e.g. 300000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="paymentMethod">Payment Method</label>
                            <select class="form-select" id="paymentMethod" name="payment_method" required>
                                <option value="" selected disabled>Select method</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="LC">LC</option>
                                <option value="SWIFT">SWIFT</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewPaymentModal" tabindex="-1" aria-labelledby="viewPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Payment details</p>
                    <h2 class="modal-title fs-5" id="viewPaymentModalLabel">Payment <span data-payment-detail="id">#1</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Payment Status</span>
                    <span class="status-badge status-badge--warning" data-payment-detail="status">Partially Paid</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Order ID</dt><dd data-payment-detail="orderId">#1</dd></div>
                    <div><dt>Buyer</dt><dd data-payment-detail="buyerName">ABC Fashion</dd></div>
                    <div><dt>Total Amount</dt><dd data-payment-detail="totalAmount">৳500,000</dd></div>
                    <div><dt>Paid Amount</dt><dd class="text-success" data-payment-detail="paidAmount">৳300,000</dd></div>
                    <div><dt>Remaining Amount</dt><dd class="text-danger" data-payment-detail="remainingAmount">৳200,000</dd></div>
                    <div><dt>Payment Method</dt><dd data-payment-detail="method">Bank Transfer</dd></div>
                    <div class="detail-grid__wide"><dt>Payment Date</dt><dd data-payment-detail="date">12 Aug 2026</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this payment"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Payment</button>
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