<?php
require_once __DIR__ . '/../config/auth.php';

garments_session_start_safe();
$user = garments_current_user();
if (!$user) {
    header('Location: ../login.php');
    exit;
}

if (strtolower((string) ($user['role'] ?? '')) !== 'incharge') {
    header('Location: ../login.php?error=unauthorized');
    exit;
}

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$dashboardMetrics = [
    ['label' => 'Total Orders', 'value' => '0', 'detail' => 'Loading from Oracle...', 'icon' => 'bi-clipboard2-check', 'tone' => 'primary'],
    ['label' => 'Workers', 'value' => '0', 'detail' => 'Loading from Oracle...', 'icon' => 'bi-people', 'tone' => 'indigo'],
    ['label' => 'Production Stages', 'value' => '0', 'detail' => 'Loading from Oracle...', 'icon' => 'bi-diagram-3', 'tone' => 'teal'],
    ['label' => 'Suppliers', 'value' => '0', 'detail' => 'Loading from Oracle...', 'icon' => 'bi-truck-flatbed', 'tone' => 'orange'],
    ['label' => 'Buyers', 'value' => '0', 'detail' => 'Loading from Oracle...', 'icon' => 'bi-building', 'tone' => 'purple'],
    ['label' => 'Pending Payments', 'value' => '0', 'detail' => 'Loading from Oracle...', 'icon' => 'bi-credit-card', 'tone' => 'rose'],
];

$conn = garments_db_connect();
if ($conn) {
    $queries = [
        'orders' => 'SELECT COUNT(*) AS total FROM Orders',
        'workers' => 'SELECT COUNT(*) AS total FROM Worker',
        'stages' => 'SELECT COUNT(*) AS total FROM Production_Stage',
        'suppliers' => 'SELECT COUNT(*) AS total FROM Supplier',
        'buyers' => 'SELECT COUNT(*) AS total FROM Buyer',
        'payments' => "SELECT COUNT(*) AS total FROM Payment WHERE Remaining_Amount > 0"
    ];

    foreach ($queries as $key => $sql) {
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        if ($row) {
            $value = (int) ($row['TOTAL'] ?? 0);
            if ($key === 'payments') {
                $dashboardMetrics[5]['value'] = '₹' . number_format($value, 0);
                $dashboardMetrics[5]['detail'] = 'Pending payment records';
            } else {
                $idx = ['orders' => 0, 'workers' => 1, 'stages' => 2, 'suppliers' => 3, 'buyers' => 4][$key];
                $dashboardMetrics[$idx]['value'] = (string) $value;
                $dashboardMetrics[$idx]['detail'] = 'Live count from Oracle';
            }
        }
        oci_free_statement($stmt);
    }

    oci_close($conn);
}

$recentOrders = [
    ['id' => '#6', 'buyer' => 'Tokyo Fashion', 'description' => '700 Sweatshirts', 'orderDate' => '06 Aug 2026', 'deliveryDate' => '22 Aug 2026', 'status' => 'In production', 'statusClass' => 'primary'],
    ['id' => '#5', 'buyer' => 'Elite Clothing', 'description' => '1,200 Sports Jerseys', 'orderDate' => '05 Aug 2026', 'deliveryDate' => '19 Aug 2026', 'status' => 'Quality check', 'statusClass' => 'warning'],
    ['id' => '#4', 'buyer' => 'Classic Apparel', 'description' => '500 Jackets', 'orderDate' => '04 Aug 2026', 'deliveryDate' => '20 Aug 2026', 'status' => 'Pending', 'statusClass' => 'muted'],
    ['id' => '#3', 'buyer' => 'Urban Style', 'description' => '1,500 T-Shirts', 'orderDate' => '03 Aug 2026', 'deliveryDate' => '16 Aug 2026', 'status' => 'Ready to ship', 'statusClass' => 'success'],
];

$productionStages = [
    ['name' => 'Cutting', 'workers' => '15 workers', 'progress' => '100%', 'barClass' => 'progress-fill--100', 'status' => 'Completed', 'statusClass' => 'success'],
    ['name' => 'Sewing', 'workers' => '20 workers', 'progress' => '68%', 'barClass' => 'progress-fill--68', 'status' => 'In progress', 'statusClass' => 'primary'],
    ['name' => 'Embroidery', 'workers' => '10 workers', 'progress' => '100%', 'barClass' => 'progress-fill--100', 'status' => 'Completed', 'statusClass' => 'success'],
    ['name' => 'Printing', 'workers' => '8 workers', 'progress' => '32%', 'barClass' => 'progress-fill--32', 'status' => 'Needs attention', 'statusClass' => 'warning'],
];

$shipments = [
    ['id' => '#1', 'tracking' => 'TRK100001', 'destination' => 'United States', 'date' => '18 Aug 2026', 'status' => 'Dispatched', 'statusClass' => 'success', 'icon' => 'bi-check2'],
    ['id' => '#2', 'tracking' => 'TRK100002', 'destination' => 'Germany', 'date' => '19 Aug 2026', 'status' => 'Scheduled', 'statusClass' => 'primary', 'icon' => 'bi-calendar-event'],
    ['id' => '#3', 'tracking' => 'TRK100003', 'destination' => 'Canada', 'date' => '20 Aug 2026', 'status' => 'Preparing', 'statusClass' => 'muted', 'icon' => 'bi-box-seam'],
];

$activities = [
    ['icon' => 'bi-patch-check', 'tone' => 'success', 'title' => 'Inspection #3 completed', 'text' => '1,485 units passed final inspection.', 'time' => '20 min ago'],
    ['icon' => 'bi-gear-wide-connected', 'tone' => 'primary', 'title' => 'Sewing stage updated', 'text' => 'Stage progress moved to 68%.', 'time' => '1 hr ago'],
    ['icon' => 'bi-credit-card', 'tone' => 'orange', 'title' => 'Payment #4 recorded', 'text' => '৳500,000 received via SWIFT.', 'time' => '3 hrs ago'],
    ['icon' => 'bi-truck', 'tone' => 'purple', 'title' => 'Shipment #2 scheduled', 'text' => 'Berlin delivery confirmed for 19 Aug.', 'time' => 'Yesterday'],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="dashboard-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><span>Home</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                <h1 id="dashboard-heading">Dashboard</h1>
                <p>Track factory operations, orders, production, and delivery activity in one place.</p>
            </div>
            <a class="btn btn-primary page-hero__action" href="orders.php">
                <i class="bi bi-clipboard2-plus" aria-hidden="true"></i>
                <span>View Orders</span>
            </a>
        </section>

        <section aria-label="Factory overview">
            <div class="row g-3">
                <?php foreach ($dashboardMetrics as $metric): ?>
                    <div class="col-12 col-sm-6 col-xl-4 col-xxl-2">
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

        <section class="row g-4 dashboard-section" aria-label="Order and production details">
            <div class="col-12 col-xxl-8">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Order management</p>
                            <h2>Recent Orders</h2>
                        </div>
                        <a class="text-link" href="orders.php">View all <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="table-responsive dashboard-table-wrap">
                        <table class="table dashboard-table align-middle mb-0">
                            <caption class="visually-hidden">Four most recent garments orders</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Buyer</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Order Date</th>
                                    <th scope="col">Estimated Delivery</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td><span class="table-id"><?= $escape($order['id']); ?></span></td>
                                        <td><strong><?= $escape($order['buyer']); ?></strong></td>
                                        <td><?= $escape($order['description']); ?></td>
                                        <td><?= $escape($order['orderDate']); ?></td>
                                        <td><?= $escape($order['deliveryDate']); ?></td>
                                        <td><span class="status-badge status-badge--<?= $escape($order['statusClass']); ?>"><?= $escape($order['status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xxl-4">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Production floor</p>
                            <h2>Production Progress</h2>
                        </div>
                        <span class="card-period">August 2026</span>
                    </div>
                    <div class="stage-list">
                        <?php foreach ($productionStages as $stage): ?>
                            <div class="stage-item">
                                <div class="stage-item__header">
                                    <div>
                                        <strong><?= $escape($stage['name']); ?></strong>
                                        <span><?= $escape($stage['workers']); ?></span>
                                    </div>
                                    <div class="stage-item__value">
                                        <strong><?= $escape($stage['progress']); ?></strong>
                                        <span class="status-badge status-badge--<?= $escape($stage['statusClass']); ?>"><?= $escape($stage['status']); ?></span>
                                    </div>
                                </div>
                                <div class="progress stage-progress" role="progressbar" aria-label="<?= $escape($stage['name']); ?> progress" aria-valuenow="<?= (int) $stage['progress']; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar <?= $escape($stage['barClass']); ?>"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a class="card-footer-link" href="production.php">Open production board <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                </article>
            </div>
        </section>

        <section class="row g-4 dashboard-section" aria-label="Shipment and activity details">
            <div class="col-12 col-xxl-7">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Logistics</p>
                            <h2>Shipment Status</h2>
                        </div>
                        <a class="text-link" href="shipment.php">Shipment details <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="shipment-list">
                        <?php foreach ($shipments as $shipment): ?>
                            <article class="shipment-item">
                                <span class="shipment-item__icon shipment-item__icon--<?= $escape($shipment['statusClass']); ?>"><i class="bi <?= $escape($shipment['icon']); ?>" aria-hidden="true"></i></span>
                                <div class="shipment-item__details">
                                    <div>
                                        <span class="shipment-id">Shipment <?= $escape($shipment['id']); ?></span>
                                        <strong><?= $escape($shipment['destination']); ?></strong>
                                    </div>
                                    <span><?= $escape($shipment['tracking']); ?> · Estimated delivery <?= $escape($shipment['date']); ?></span>
                                </div>
                                <span class="status-badge status-badge--<?= $escape($shipment['statusClass']); ?>"><?= $escape($shipment['status']); ?></span>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xxl-5">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Activity feed</p>
                            <h2>Recent Activities</h2>
                        </div>
                    </div>
                    <div class="activity-list">
                        <?php foreach ($activities as $activity): ?>
                            <article class="activity-item">
                                <span class="activity-item__icon activity-item__icon--<?= $escape($activity['tone']); ?>"><i class="bi <?= $escape($activity['icon']); ?>" aria-hidden="true"></i></span>
                                <div class="activity-item__content">
                                    <div class="activity-item__title-row">
                                        <strong><?= $escape($activity['title']); ?></strong>
                                        <time><?= $escape($activity['time']); ?></time>
                                    </div>
                                    <p><?= $escape($activity['text']); ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </article>
            </div>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
