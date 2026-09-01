<?php
require_once __DIR__ . '/../config/auth.php';

garments_session_start_safe();
$user = garments_current_user();
if (!$user) {
    header('Location: ../login.php');
    exit;
}

if (strtolower((string) ($user['role'] ?? '')) !== 'worker') {
    header('Location: ../login.php?error=unauthorized');
    exit;
}

$pageTitle = 'Worker Dashboard';
$activePage = 'dashboard';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$workerProfile = [
    'name' => 'Rahim Ahmed',
    'id' => 'WRK-001',
    'department' => 'Sewing',
    'shift' => 'Day Shift',
];

$dashboardMetrics = [
    ['label' => 'Assigned Tasks', 'value' => '12', 'detail' => 'Across 4 active orders', 'icon' => 'bi-clipboard-check', 'tone' => 'primary'],
    ['label' => 'Completed Tasks', 'value' => '7', 'detail' => '3 tasks finished today', 'icon' => 'bi-check2-circle', 'tone' => 'success'],
    ['label' => 'Pending Tasks', 'value' => '5', 'detail' => '2 due before 5:00 PM', 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
    ['label' => 'Attendance %', 'value' => '96%', 'detail' => '28 days present this month', 'icon' => 'bi-calendar-check', 'tone' => 'indigo'],
    ['label' => "Today's Production", 'value' => '145', 'detail' => 'Units completed so far', 'icon' => 'bi-diagram-3', 'tone' => 'teal'],
];

$taskSummary = [
    ['task' => 'Attach Collar', 'order' => 'ORD-2026-015', 'stage' => 'Sewing', 'target' => '120 units', 'completed' => '94 units', 'statusClass' => 'primary', 'status' => 'In progress'],
    ['task' => 'Side Seam Assembly', 'order' => 'ORD-2026-018', 'stage' => 'Assembly', 'target' => '90 units', 'completed' => '72 units', 'statusClass' => 'warning', 'status' => 'Pending'],
    ['task' => 'Hemming', 'order' => 'ORD-2026-021', 'stage' => 'Finishing', 'target' => '80 units', 'completed' => '80 units', 'statusClass' => 'success', 'status' => 'Completed'],
];

$shiftInfo = [
    ['label' => 'Shift Time', 'value' => '08:00 AM - 05:00 PM'],
    ['label' => 'Supervisor', 'value' => 'Karim Uddin'],
    ['label' => 'Production Floor', 'value' => 'Sewing Line 02'],
];

$recentNotifications = [
    ['title' => 'New Task Assigned', 'message' => 'Hemming task added for ORD-2026-021.', 'time' => '12 mins ago', 'tone' => 'primary'],
    ['title' => 'Shift Updated', 'message' => 'Your shift timing has been updated for today.', 'time' => '1 hr ago', 'tone' => 'warning'],
    ['title' => 'Target Increased', 'message' => 'Daily production target increased by 15 units.', 'time' => '3 hrs ago', 'tone' => 'success'],
    ['title' => 'Production Approved', 'message' => 'Last batch from Sewing Line 02 was approved.', 'time' => 'Yesterday', 'tone' => 'indigo'],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/worker_sidebar.php';
require_once __DIR__ . '/../includes/worker_navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="worker-dashboard-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><span>Home</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Worker Dashboard</li>
                    </ol>
                </nav>
                <h1 id="worker-dashboard-heading">Welcome back, <?= $escape($workerProfile['name']); ?></h1>
                <p>Track your daily performance, tasks, and production progress.</p>
            </div>
            <a class="btn btn-primary page-hero__action" href="worker_tasks.php">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                <span>View My Tasks</span>
            </a>
        </section>

        <section aria-label="Worker overview cards">
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

        <section class="row g-4 dashboard-section" aria-label="Worker detail panels">
            <div class="col-12 col-xl-8">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Today</p>
                            <h2>Today's Task Summary</h2>
                        </div>
                        <a class="text-link" href="worker_tasks.php">Open tasks <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="table-responsive dashboard-table-wrap">
                        <table class="table dashboard-table align-middle mb-0">
                            <caption class="visually-hidden">Today’s assigned tasks</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Task Name</th>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Stage</th>
                                    <th scope="col">Target</th>
                                    <th scope="col">Completed</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($taskSummary as $task): ?>
                                    <tr>
                                        <td><strong><?= $escape($task['task']); ?></strong></td>
                                        <td><span class="table-id"><?= $escape($task['order']); ?></span></td>
                                        <td><?= $escape($task['stage']); ?></td>
                                        <td><?= $escape($task['target']); ?></td>
                                        <td><?= $escape($task['completed']); ?></td>
                                        <td><span class="status-badge status-badge--<?= $escape($task['statusClass']); ?>"><?= $escape($task['status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-4">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Profile</p>
                            <h2>Worker Overview</h2>
                        </div>
                    </div>

                    <div class="summary-profile">
                        <div class="summary-profile__header">
                            <span class="summary-profile__avatar">RA</span>
                            <div>
                                <strong><?= $escape($workerProfile['name']); ?></strong>
                                <small><?= $escape($workerProfile['id']); ?></small>
                            </div>
                        </div>

                        <div class="summary-profile__meta">
                            <div>
                                <span>Department</span>
                                <strong><?= $escape($workerProfile['department']); ?></strong>
                            </div>
                            <div>
                                <span>Current Shift</span>
                                <strong><?= $escape($workerProfile['shift']); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="stacked-list">
                        <?php foreach ($shiftInfo as $item): ?>
                            <div class="stacked-list__item">
                                <span><?= $escape($item['label']); ?></span>
                                <strong><?= $escape($item['value']); ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>
            </div>
        </section>

        <section class="row g-4 dashboard-section" aria-label="Notifications and production details">
            <div class="col-12 col-xl-7">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Updates</p>
                            <h2>Recent Notifications</h2>
                        </div>
                        <a class="text-link" href="worker_notifications.php">View all <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="activity-list">
                        <?php foreach ($recentNotifications as $notification): ?>
                            <article class="activity-item">
                                <span class="activity-item__icon activity-item__icon--<?= $escape($notification['tone']); ?>">
                                    <i class="bi bi-bell" aria-hidden="true"></i>
                                </span>
                                <div class="activity-item__content">
                                    <div class="activity-item__title-row">
                                        <strong><?= $escape($notification['title']); ?></strong>
                                        <time><?= $escape($notification['time']); ?></time>
                                    </div>
                                    <p><?= $escape($notification['message']); ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-5">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Production</p>
                            <h2>Today's Output</h2>
                        </div>
                    </div>

                    <div class="stage-list">
                        <div class="stage-item">
                            <div class="stage-item__header">
                                <div>
                                    <strong>Sewing</strong>
                                    <span>Line 02</span>
                                </div>
                                <div class="stage-item__value">
                                    <strong>78%</strong>
                                    <span class="status-badge status-badge--success">On track</span>
                                </div>
                            </div>
                            <div class="progress stage-progress" role="progressbar" aria-label="Sewing progress" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar progress-fill--78"></div>
                            </div>
                        </div>

                        <div class="stage-item">
                            <div class="stage-item__header">
                                <div>
                                    <strong>Finishing</strong>
                                    <span>Line 03</span>
                                </div>
                                <div class="stage-item__value">
                                    <strong>62%</strong>
                                    <span class="status-badge status-badge--primary">Active</span>
                                </div>
                            </div>
                            <div class="progress stage-progress" role="progressbar" aria-label="Finishing progress" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar progress-fill--62"></div>
                            </div>
                        </div>
                    </div>
                    <a class="card-footer-link" href="worker_production.php">Open production panel <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
                </article>
            </div>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
