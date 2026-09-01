<?php
/**
 * Accounts frontend prototype.
 * Static records are based on the Accounts entity and its relationships
 * with Payments, Employees, and Suppliers from the supplied schema.
 */
$pageTitle = 'Accounts';
$activePage = 'accounts';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$accountMetrics = [
    ['label' => 'Total Transactions', 'value' => '5', 'detail' => 'Transactions logged in August', 'icon' => 'bi-list-ol', 'tone' => 'primary'],
    ['label' => 'Total Credited', 'value' => '৳920K', 'detail' => 'Sum of all incoming funds', 'icon' => 'bi-arrow-down-circle', 'tone' => 'success'],
    ['label' => 'Total Debited', 'value' => '৳585K', 'detail' => 'Sum of all outgoing funds', 'icon' => 'bi-arrow-up-circle', 'tone' => 'rose'],
    ['label' => 'Net Balance', 'value' => '৳335K', 'detail' => 'Current net account balance', 'icon' => 'bi-bank2', 'tone' => 'indigo'],
];

$transactions = [
    [
        'id' => 'TXN001', 'date' => '12 Aug 2026', 'amount' => '300000',
        'status' => 'Credited', 'statusKey' => 'credited', 'statusClass' => 'success',
        'bank' => 'City Bank', 'bankKey' => 'city-bank',
        'description' => 'Payment from ABC Fashion (Order #1)', 'source' => 'Payment ID #1',
    ],
    [
        'id' => 'TXN002', 'date' => '13 Aug 2026', 'amount' => '620000',
        'status' => 'Credited', 'statusKey' => 'credited', 'statusClass' => 'success',
        'bank' => 'Standard Chartered', 'bankKey' => 'sc-bank',
        'description' => 'Payment from Global Wear (Order #2)', 'source' => 'Payment ID #2',
    ],
    [
        'id' => 'TXN003', 'date' => '14 Aug 2026', 'amount' => '500000',
        'status' => 'Debited', 'statusKey' => 'debited', 'statusClass' => 'rose',
        'bank' => 'City Bank', 'bankKey' => 'city-bank',
        'description' => 'Payment to Square Textiles (BOM #1)', 'source' => 'BOM ID #1',
    ],
    [
        'id' => 'TXN004', 'date' => '15 Aug 2026', 'amount' => '60000',
        'status' => 'Debited', 'statusKey' => 'debited', 'statusClass' => 'rose',
        'bank' => 'Brac Bank', 'bankKey' => 'brac-bank',
        'description' => 'Salary for Rahim Ahmed (Incharge)', 'source' => 'Employee ID #101',
    ],
    [
        'id' => 'TXN005', 'date' => '15 Aug 2026', 'amount' => '25000',
        'status' => 'Debited', 'statusKey' => 'debited', 'statusClass' => 'rose',
        'bank' => 'Brac Bank', 'bankKey' => 'brac-bank',
        'description' => 'Salary for Jamal Hossain (Worker)', 'source' => 'Employee ID #201',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="accounts-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Accounts</li>
                    </ol>
                </nav>
                <h1 id="accounts-heading">Accounts</h1>
                <p>Manage all financial transactions, including credits and debits.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Transaction</span>
            </button>
        </section>

        <section aria-label="Account overview">
            <div class="row g-3">
                <?php foreach ($accountMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="accounts-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Transaction history</p>
                        <h2 id="accounts-table-heading">All Transactions</h2>
                    </div>
                    <span class="card-period">5 transactions</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by description or source" aria-label="Search transactions" data-table-search="#accountsTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter transactions by status" data-table-filter="#accountsTable" data-filter-key="status">
                            <option value="all">All Statuses</option>
                            <option value="credited">Credited</option>
                            <option value="debited">Debited</option>
                        </select>
                        <select class="form-select" aria-label="Filter transactions by bank" data-table-filter="#accountsTable" data-filter-key="bank">
                            <option value="all">All Banks</option>
                            <option value="city-bank">City Bank</option>
                            <option value="sc-bank">Standard Chartered</option>
                            <option value="brac-bank">Brac Bank</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table accounts-table align-middle mb-0" id="accountsTable" data-table-label="transactions">
                        <caption class="visually-hidden">Static transaction data with status, amount, and description</caption>
                        <thead>
                            <tr>
                                <th scope="col">Transaction ID</th>
                                <th scope="col">Date</th>
                                <th scope="col">Description</th>
                                <th scope="col">Status</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Associated Bank</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr data-search-row data-status="<?= $escape($transaction['statusKey']); ?>" data-bank="<?= $escape($transaction['bankKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($transaction['id']); ?></span></td>
                                    <td><?= $escape($transaction['date']); ?></td>
                                    <td><strong><?= $escape($transaction['description']); ?></strong></td>
                                    <td><span class="status-badge status-badge--<?= $escape($transaction['statusClass']); ?>"><?= $escape($transaction['status']); ?></span></td>
                                    <td><strong class="text-<?= $transaction['statusKey'] === 'credited' ? 'success' : 'danger' ?>">৳<?= $escape(number_format((float) $transaction['amount'])); ?></strong></td>
                                    <td><span class="table-row-meta"><?= $escape($transaction['bank']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View Transaction #<?= $escape($transaction['id']); ?>"
                                                aria-label="View Transaction #<?= $escape($transaction['id']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewTransactionModal"
                                                data-transaction-id="#<?= $escape($transaction['id']); ?>"
                                                data-transaction-date="<?= $escape($transaction['date']); ?>"
                                                data-transaction-description="<?= $escape($transaction['description']); ?>"
                                                data-transaction-status="<?= $escape($transaction['status']); ?>"
                                                data-transaction-status-class="<?= $escape($transaction['statusClass']); ?>"
                                                data-transaction-amount="৳<?= $escape(number_format((float) $transaction['amount'])); ?>"
                                                data-transaction-bank="<?= $escape($transaction['bank']); ?>"
                                                data-transaction-source="<?= $escape($transaction['source']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="7">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No transactions match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#accountsTable">Showing 1–<?= count($transactions); ?> of <?= count($transactions); ?> transactions</p>
                    <nav aria-label="Transactions pagination">
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

<!-- UI-only modal: Add Transaction -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
<form data-accounts-form data-backend-resource="accounts">
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Financials</p>
                        <h2 class="modal-title fs-5" id="addTransactionModalLabel">Add New Transaction</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="transactionId">Transaction ID</label>
                            <input class="form-control" id="transactionId" name="transaction_id" type="text" placeholder="e.g. TXN006" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="transactionDate">Date</label>
                            <input class="form-control" id="transactionDate" name="date" type="date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="transactionAmount">Amount</label>
                            <input class="form-control" id="transactionAmount" name="amount" type="number" min="0" placeholder="e.g. 15000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="transactionStatus">Status</label>
                            <select class="form-select" id="transactionStatus" name="status" required>
                                <option value="" selected disabled>Select status</option>
                                <option value="Credited">Credited</option>
                                <option value="Debited">Debited</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="transactionBank">Associated Bank</label>
                            <input class="form-control" id="transactionBank" name="associated_bank" type="text" placeholder="e.g. City Bank" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewTransactionModal" tabindex="-1" aria-labelledby="viewTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Transaction details</p>
                    <h2 class="modal-title fs-5" id="viewTransactionModalLabel">Transaction <span data-transaction-detail="id">#TXN001</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Transaction Status</span>
                    <span class="status-badge status-badge--success" data-transaction-detail="status">Credited</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Transaction Date</dt><dd data-transaction-detail="date">12 Aug 2026</dd></div>
                    <div><dt>Amount</dt><dd data-transaction-detail="amount">৳300,000</dd></div>
                    <div><dt>Associated Bank</dt><dd data-transaction-detail="bank">City Bank</dd></div>
                    <div><dt>Source ID</dt><dd data-transaction-detail="source">Payment ID #1</dd></div>
                    <div class="detail-grid__wide"><dt>Description</dt><dd data-transaction-detail="description">Payment from ABC Fashion (Order #1)</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this transaction"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Transaction</button>
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
