<?php
require_once __DIR__ . '/../config/auth.php';

garments_session_start_safe();
if (!garments_current_user()) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = 'Buyers';
$activePage = 'buyers';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$buyerMetrics = [
    ['label' => 'Total Buyers', 'value' => '0', 'detail' => 'Buyer accounts in the order register', 'icon' => 'bi-building', 'tone' => 'primary'],
    ['label' => 'Buyer Contacts', 'value' => '0', 'detail' => 'Registered multivalued contact numbers', 'icon' => 'bi-telephone', 'tone' => 'indigo'],
    ['label' => 'Fully Paid', 'value' => '0', 'detail' => 'Buyer payments with no remaining amount', 'icon' => 'bi-check2-circle', 'tone' => 'teal'],
    ['label' => 'Export Markets', 'value' => '0', 'detail' => 'Destinations linked through shipments', 'icon' => 'bi-globe2', 'tone' => 'purple'],
];

$buyers = [];
$conn = garments_db_connect();
if ($conn) {
    $buyerMetrics[0]['value'] = (string) (int) ((garments_db_fetch_one('SELECT COUNT(*) AS total FROM Buyer')['TOTAL'] ?? 0));
    $buyerMetrics[1]['value'] = (string) (int) ((garments_db_fetch_one('SELECT COUNT(*) AS total FROM Buyer_Contact')['TOTAL'] ?? 0));
    $buyerMetrics[2]['value'] = (string) (int) ((garments_db_fetch_one("SELECT COUNT(*) AS total FROM Payment p JOIN Rel_Buyer_Payment rbp ON rbp.Payment_ID = p.Payment_ID WHERE p.Remaining_Amount = 0")['TOTAL'] ?? 0));
    $buyerMetrics[3]['value'] = (string) (int) ((garments_db_fetch_one('SELECT COUNT(DISTINCT Destination) AS total FROM Shipment')['TOTAL'] ?? 0));

    $buyerSql = "
        SELECT
            b.Buyer_ID AS id,
            b.Name AS name,
            b.Brand AS brand,
            COALESCE((SELECT bc.Contact_Number FROM Buyer_Contact bc WHERE bc.Buyer_ID = b.Buyer_ID AND ROWNUM = 1), 'N/A') AS contact,
            b.Email AS email,
            b.Account_No AS account_no,
            b.Address AS address,
            o.Order_ID AS order_id,
            o.Description AS order_description,
            s.Tracking_Number AS tracking_number,
            s.Destination AS destination,
            CASE
                WHEN p.Remaining_Amount IS NULL OR p.Remaining_Amount = 0 THEN 'Paid'
                ELSE 'Partially Paid'
            END AS payment_status,
            CASE
                WHEN p.Remaining_Amount IS NULL OR p.Remaining_Amount = 0 THEN 'paid'
                ELSE 'partial'
            END AS payment_key,
            CASE
                WHEN p.Remaining_Amount IS NULL OR p.Remaining_Amount = 0 THEN 'success'
                ELSE 'warning'
            END AS status_class
        FROM Buyer b
        LEFT JOIN Rel_Buyer_Order rbo ON rbo.Buyer_ID = b.Buyer_ID
        LEFT JOIN Orders o ON o.Order_ID = rbo.Order_ID
        LEFT JOIN Rel_Shipment_Buyer rsb ON rsb.Buyer_ID = b.Buyer_ID
        LEFT JOIN Shipment s ON s.Shipment_ID = rsb.Shipment_ID
        LEFT JOIN Rel_Buyer_Payment rbp ON rbp.Buyer_ID = b.Buyer_ID
        LEFT JOIN Payment p ON p.Payment_ID = rbp.Payment_ID
        ORDER BY b.Buyer_ID
    ";

    $dbBuyers = garments_db_fetch_all($buyerSql);
    if (!empty($dbBuyers)) {
        foreach ($dbBuyers as $row) {
            $name = (string) ($row['NAME'] ?? '');
            $initials = strtoupper(preg_replace('/[^A-Z]/i', '', substr($name, 0, 2) ?: 'B')) ?: 'B';
            $region = strtolower((string) ($row['DESTINATION'] ?? ''));
            $regionKey = 'asia';
            if (stripos($region, 'us') !== false || stripos($region, 'canada') !== false || stripos($region, 'usa') !== false) {
                $regionKey = 'americas';
            } elseif (stripos($region, 'germany') !== false || stripos($region, 'france') !== false || stripos($region, 'uk') !== false || stripos($region, 'britain') !== false) {
                $regionKey = 'europe';
            }
            $buyers[] = [
                'id' => (string) ($row['ID'] ?? ''),
                'name' => $name,
                'brand' => (string) ($row['BRAND'] ?? $name),
                'initials' => $initials,
                'contact' => (string) ($row['CONTACT'] ?? 'N/A'),
                'email' => (string) ($row['EMAIL'] ?? 'N/A'),
                'account' => (string) ($row['ACCOUNT_NO'] ?? 'N/A'),
                'address' => (string) ($row['ADDRESS'] ?? 'N/A'),
                'region' => $regionKey,
                'order' => (string) (($row['ORDER_ID'] ?? '') !== '' ? '#' . $row['ORDER_ID'] . ' · ' . ($row['ORDER_DESCRIPTION'] ?? 'Order') : 'N/A'),
                'shipment' => (string) (($row['TRACKING_NUMBER'] ?? '') !== '' ? $row['TRACKING_NUMBER'] . ' · ' . ($row['DESTINATION'] ?? 'N/A') : 'N/A'),
                'paymentStatus' => (string) ($row['PAYMENT_STATUS'] ?? 'Paid'),
                'paymentKey' => (string) ($row['PAYMENT_KEY'] ?? 'paid'),
                'statusClass' => (string) ($row['STATUS_CLASS'] ?? 'success'),
            ];
        }
    }
}

if (empty($buyers)) {
    $buyers = [
        ['id' => '1', 'name' => 'ABC Fashion', 'brand' => 'ABC Brand', 'initials' => 'AF', 'contact' => '+1 202 555 0101', 'email' => 'abc@brand.com', 'account' => 'ACC1001', 'address' => 'New York, USA', 'region' => 'americas', 'order' => '#1 · 1,000 Polo Shirts', 'shipment' => 'TRK100001 · USA', 'paymentStatus' => 'Partially Paid', 'paymentKey' => 'partial', 'statusClass' => 'warning'],
        ['id' => '2', 'name' => 'Global Wear', 'brand' => 'Global Wear', 'initials' => 'GW', 'contact' => '+49 30 1234 567', 'email' => 'global@wear.com', 'account' => 'ACC1002', 'address' => 'Berlin, Germany', 'region' => 'europe', 'order' => '#2 · 800 Hoodies', 'shipment' => 'TRK100002 · Germany', 'paymentStatus' => 'Paid', 'paymentKey' => 'paid', 'statusClass' => 'success'],
        ['id' => '3', 'name' => 'Urban Style', 'brand' => 'Urban Style', 'initials' => 'US', 'contact' => '+1 416 555 1234', 'email' => 'urban@style.com', 'account' => 'ACC1003', 'address' => 'Toronto, Canada', 'region' => 'americas', 'order' => '#3 · 1,500 T-Shirts', 'shipment' => 'TRK100003 · Canada', 'paymentStatus' => 'Partially Paid', 'paymentKey' => 'partial', 'statusClass' => 'warning'],
        ['id' => '4', 'name' => 'Classic Apparel', 'brand' => 'Classic Apparel', 'initials' => 'CA', 'contact' => '+44 20 7123 4567', 'email' => 'classic@apparel.com', 'account' => 'ACC1004', 'address' => 'London, UK', 'region' => 'europe', 'order' => '#4 · 500 Jackets', 'shipment' => 'TRK100004 · UK', 'paymentStatus' => 'Partially Paid', 'paymentKey' => 'partial', 'statusClass' => 'warning'],
        ['id' => '5', 'name' => 'Elite Clothing', 'brand' => 'Elite Clothing', 'initials' => 'EC', 'contact' => '+33 1 4012 3456', 'email' => 'elite@clothing.com', 'account' => 'ACC1005', 'address' => 'Paris, France', 'region' => 'europe', 'order' => '#5 · 1,200 Sports Jerseys', 'shipment' => 'TRK100005 · France', 'paymentStatus' => 'Paid', 'paymentKey' => 'paid', 'statusClass' => 'success'],
        ['id' => '6', 'name' => 'Tokyo Fashion', 'brand' => 'Tokyo Fashion', 'initials' => 'TF', 'contact' => '+81 3 1234 5678', 'email' => 'tokyo@fashion.com', 'account' => 'ACC1006', 'address' => 'Tokyo, Japan', 'region' => 'asia', 'order' => '#6 · 700 Sweatshirts', 'shipment' => 'TRK100006 · Japan', 'paymentStatus' => 'Partially Paid', 'paymentKey' => 'partial', 'statusClass' => 'warning'],
    ];
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="buyers-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buyers</li>
                    </ol>
                </nav>
                <h1 id="buyers-heading">Buyers</h1>
                <p>Maintain buyer profiles, contacts, account details, and their connected order and shipment records.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addBuyerModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Buyer</span>
            </button>
        </section>

        <section aria-label="Buyer overview">
            <div class="row g-3">
                <?php foreach ($buyerMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="buyers-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Buyer directory</p>
                        <h2 id="buyers-table-heading">All Buyers</h2>
                    </div>
                    <span class="card-period">6 active accounts</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by buyer, brand, email, or account" aria-label="Search buyers" data-table-search="#buyersTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter buyers by payment status" data-table-filter="#buyersTable" data-filter-key="payment">
                            <option value="all">All payment statuses</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partially Paid</option>
                        </select>
                        <select class="form-select" aria-label="Filter buyers by region" data-table-filter="#buyersTable" data-filter-key="region">
                            <option value="all">All regions</option>
                            <option value="americas">Americas</option>
                            <option value="europe">Europe</option>
                            <option value="asia">Asia</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table buyers-table align-middle mb-0" id="buyersTable" data-table-label="buyers">
                        <caption class="visually-hidden">Static buyer profiles and their relationship-derived order, shipment, and payment details</caption>
                        <thead>
                            <tr>
                                <th scope="col">Buyer ID</th>
                                <th scope="col">Buyer &amp; Brand</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Email</th>
                                <th scope="col">Account No.</th>
                                <th scope="col">Address</th>
                                <th scope="col">Linked Order</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($buyers as $buyer): ?>
                                <tr data-search-row data-payment="<?= $escape($buyer['paymentKey']); ?>" data-region="<?= $escape($buyer['region']); ?>">
                                    <td><span class="table-id">#<?= $escape($buyer['id']); ?></span></td>
                                    <td>
                                        <span class="buyer-identity">
                                            <span class="buyer-identity__avatar"><?= $escape($buyer['initials']); ?></span>
                                            <span>
                                                <strong><?= $escape($buyer['name']); ?></strong>
                                                <small><?= $escape($buyer['brand']); ?></small>
                                            </span>
                                        </span>
                                    </td>
                                    <td><span class="contact-value"><i class="bi bi-telephone" aria-hidden="true"></i><?= $escape($buyer['contact']); ?></span></td>
                                    <td><a class="table-email" href="mailto:<?= $escape($buyer['email']); ?>"><?= $escape($buyer['email']); ?></a></td>
                                    <td><span class="account-number"><?= $escape($buyer['account']); ?></span></td>
                                    <td><span class="table-row-meta"><?= $escape($buyer['address']); ?></span></td>
                                    <td><span class="table-row-meta"><?= $escape($buyer['order']); ?></span></td>
                                    <td><span class="status-badge status-badge--<?= $escape($buyer['statusClass']); ?>"><?= $escape($buyer['paymentStatus']); ?></span></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View <?= $escape($buyer['name']); ?>"
                                                aria-label="View <?= $escape($buyer['name']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewBuyerModal"
                                                data-buyer-id="#<?= $escape($buyer['id']); ?>"
                                                data-buyer-name="<?= $escape($buyer['name']); ?>"
                                                data-buyer-brand="<?= $escape($buyer['brand']); ?>"
                                                data-buyer-initials="<?= $escape($buyer['initials']); ?>"
                                                data-buyer-contact="<?= $escape($buyer['contact']); ?>"
                                                data-buyer-email="<?= $escape($buyer['email']); ?>"
                                                data-buyer-account="<?= $escape($buyer['account']); ?>"
                                                data-buyer-address="<?= $escape($buyer['address']); ?>"
                                                data-buyer-order="<?= $escape($buyer['order']); ?>"
                                                data-buyer-shipment="<?= $escape($buyer['shipment']); ?>"
                                                data-buyer-payment-status="<?= $escape($buyer['paymentStatus']); ?>"
                                                data-buyer-status-class="<?= $escape($buyer['statusClass']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit <?= $escape($buyer['name']); ?>" aria-label="Edit <?= $escape($buyer['name']); ?>" data-prototype-action="Edit <?= $escape($buyer['name']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button table-action-button--danger" type="button" title="Delete <?= $escape($buyer['name']); ?>" aria-label="Delete <?= $escape($buyer['name']); ?>" data-prototype-action="Delete <?= $escape($buyer['name']); ?>">
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
                                        <span>No buyers match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#buyersTable">Showing 1–6 of 6 buyers</p>
                    <nav aria-label="Buyers pagination">
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

<!-- UI-only Buyer and Buyer_Contact form. -->
<div class="modal fade" id="addBuyerModal" tabindex="-1" aria-labelledby="addBuyerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content app-modal">
<form data-buyers-form data-backend-resource="buyer">
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Buyer profile</p>
                        <h2 class="modal-title fs-5" id="addBuyerModalLabel">Add Buyer</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This form is a frontend-only preview and will not save buyer information.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="buyerId">Buyer ID</label>
                            <input class="form-control" id="buyerId" name="buyer_id" type="number" min="1" placeholder="e.g. 7" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="buyerName">Name</label>
                            <input class="form-control" id="buyerName" name="name" type="text" placeholder="e.g. Meridian Apparel" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="buyerBrand">Brand</label>
                            <input class="form-control" id="buyerBrand" name="brand" type="text" placeholder="e.g. Meridian Collection" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="buyerAccount">Account No.</label>
                            <input class="form-control" id="buyerAccount" name="account_no" type="text" placeholder="e.g. ACC1007" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="buyerEmail">Email</label>
                            <input class="form-control" id="buyerEmail" name="email" type="email" placeholder="buyer@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="buyerAddress">Address</label>
                            <input class="form-control" id="buyerAddress" name="address" type="text" placeholder="City, Country" required>
                        </div>
                    </div>

                    <div class="modal-subsection">
                        <div>
                            <h3>Contact Numbers</h3>
                            <p>Contact Number is a multivalued Buyer attribute.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="buyerContactPrimary">Primary Contact Number</label>
                                <input class="form-control" id="buyerContactPrimary" name="contact_number_primary" type="tel" placeholder="e.g. +1 202 555 0101" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="buyerContactAdditional">Additional Contact Number</label>
                                <input class="form-control" id="buyerContactAdditional" name="contact_number_additional" type="tel" placeholder="Optional">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Buyer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal populated from a selected static Buyer record. -->
<div class="modal fade" id="viewBuyerModal" tabindex="-1" aria-labelledby="viewBuyerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Buyer details</p>
                    <h2 class="modal-title fs-5" id="viewBuyerModalLabel"><span data-buyer-detail="name">ABC Fashion</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="buyer-detail-hero">
                    <span class="buyer-detail-hero__avatar" data-buyer-detail="initials">AF</span>
                    <div>
                        <strong data-buyer-detail="brand">ABC Brand</strong>
                        <span>Buyer ID <span data-buyer-detail="id">#1</span></span>
                    </div>
                    <span class="status-badge status-badge--warning" data-buyer-detail="paymentStatus">Partially Paid</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Account No.</dt><dd data-buyer-detail="account">ACC1001</dd></div>
                    <div><dt>Contact Number</dt><dd data-buyer-detail="contact">+1 202 555 0101</dd></div>
                    <div class="detail-grid__wide"><dt>Email</dt><dd data-buyer-detail="email">abc@brand.com</dd></div>
                    <div class="detail-grid__wide"><dt>Address</dt><dd data-buyer-detail="address">New York, USA</dd></div>
                    <div><dt>Linked Order</dt><dd data-buyer-detail="order">#1 · 1,000 Polo Shirts</dd></div>
                    <div><dt>Linked Shipment</dt><dd data-buyer-detail="shipment">TRK100001 · USA</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this buyer"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Buyer</button>
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
