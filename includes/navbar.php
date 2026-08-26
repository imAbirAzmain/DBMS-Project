<?php
/**
 * Shared top navigation. The logout destination can be adjusted by a page
 * through $rootBase when the application gets a central router.
 */
$rootBase = isset($rootBase) && is_string($rootBase) ? $rootBase : '../';
?>
<div class="app-content">
    <header class="app-navbar">
        <div class="navbar-left">
            <button
                class="sidebar-toggle"
                type="button"
                aria-label="Open navigation menu"
                aria-controls="appSidebar"
                aria-expanded="false"
                data-sidebar-toggle
            >
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>

            <div class="navbar-project">
                <p class="navbar-kicker">Operations workspace</p>
                <span class="navbar-title">Garments Management System</span>
            </div>
        </div>

        <div class="navbar-actions">
            <button class="navbar-icon-button" type="button" aria-label="Search records" title="Search records">
                <i class="bi bi-search" aria-hidden="true"></i>
            </button>

            <div class="dropdown">
                <button
                    class="navbar-icon-button notification-button"
                    type="button"
                    id="notificationMenu"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    aria-label="View notifications"
                >
                    <i class="bi bi-bell" aria-hidden="true"></i>
                    <span class="notification-count">3</span>
                </button>

                <div class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationMenu">
                    <div class="notification-menu__heading">
                        <span>Notifications</span>
                        <small>3 new</small>
                    </div>
                    <a class="notification-item" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>pages/production.php">
                        <span class="notification-item__icon notification-item__icon--warning"><i class="bi bi-exclamation-triangle"></i></span>
                        <span><strong>Printing stage needs review</strong><small>Production progress is below target.</small></span>
                    </a>
                    <a class="notification-item" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>pages/payments.php">
                        <span class="notification-item__icon notification-item__icon--primary"><i class="bi bi-credit-card"></i></span>
                        <span><strong>Four payments remain pending</strong><small>Outstanding balance: ৳865,000.</small></span>
                    </a>
                    <a class="notification-item" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>pages/shipment.php">
                        <span class="notification-item__icon notification-item__icon--success"><i class="bi bi-truck"></i></span>
                        <span><strong>Shipment #2 is scheduled</strong><small>Expected delivery to Germany: 19 Aug.</small></span>
                    </a>
                </div>
            </div>

            <div class="navbar-user" aria-label="Logged in user">
                <span class="user-avatar">RA</span>
                <span class="navbar-user__details">
                    <strong>Rahim Ahmed</strong>
                    <small>Factory Admin</small>
                </span>
            </div>

            <a class="btn btn-primary btn-sm navbar-logout" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>logout.php">
                <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                <span>Logout</span>
            </a>
        </div>
    </header>
