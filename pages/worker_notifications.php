<?php
/**
 * Worker notifications frontend prototype.
 * All values are dummy data and static-only, without database usage.
 */
$pageTitle = 'Notifications';
$activePage = 'notifications';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$notifications = [
    ['title' => 'New Task Assigned', 'message' => 'A new hemming task has been assigned for ORD-2026-021.', 'time' => '12 minutes ago', 'priority' => 'High', 'priorityClass' => 'danger', 'unread' => true, 'icon' => 'bi-clipboard-check'],
    ['title' => 'Shift Updated', 'message' => 'Your evening shift is scheduled from 2:00 PM to 10:00 PM today.', 'time' => '1 hour ago', 'priority' => 'Medium', 'priorityClass' => 'warning', 'unread' => true, 'icon' => 'bi-calendar-event'],
    ['title' => 'Target Increased', 'message' => 'Daily production target increased from 150 to 180 units.', 'time' => '3 hours ago', 'priority' => 'High', 'priorityClass' => 'danger', 'unread' => false, 'icon' => 'bi-graph-up-arrow'],
    ['title' => 'Production Approved', 'message' => 'The last sewing batch passed quality review and was approved.', 'time' => 'Yesterday', 'priority' => 'Low', 'priorityClass' => 'success', 'unread' => false, 'icon' => 'bi-patch-check'],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/worker_sidebar.php';
require_once __DIR__ . '/../includes/worker_navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="notifications-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><span>Home</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                    </ol>
                </nav>
                <h1 id="notifications-heading">Notifications</h1>
                <p>Review updates, task changes, and production alerts from your supervisor.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button">
                <i class="bi bi-check2-circle" aria-hidden="true"></i>
                <span>Mark All Read</span>
            </button>
        </section>

        <section class="dashboard-section" aria-labelledby="notifications-list-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Alerts</p>
                        <h2 id="notifications-list-heading">Worker Notification Center</h2>
                    </div>
                    <span class="card-period">2 unread</span>
                </div>

                <div class="notification-list">
                    <?php foreach ($notifications as $notification): ?>
                        <article class="notification-card <?= $notification['unread'] ? 'is-unread' : ''; ?>">
                            <div class="notification-card__left">
                                <span class="activity-item__icon activity-item__icon--<?= $escape($notification['priorityClass']); ?>">
                                    <i class="bi <?= $escape($notification['icon']); ?>" aria-hidden="true"></i>
                                </span>
                            </div>

                            <div class="notification-card__body">
                                <div class="notification-card__header">
                                    <div>
                                        <strong><?= $escape($notification['title']); ?></strong>
                                        <span class="notification-card__time"><?= $escape($notification['time']); ?></span>
                                    </div>
                                    <?php if ($notification['unread']): ?>
                                        <span class="notification-card__badge">Unread</span>
                                    <?php endif; ?>
                                </div>
                                <p><?= $escape($notification['message']); ?></p>
                                <div class="notification-card__footer">
                                    <span class="status-badge status-badge--<?= $escape($notification['priorityClass']); ?>"><?= $escape($notification['priority']); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </article>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
