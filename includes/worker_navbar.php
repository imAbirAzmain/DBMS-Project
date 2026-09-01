<?php
/**
 * Worker portal top navigation. This mirrors the admin header pattern while
 * exposing a worker-specific user identity and portal actions.
 */
$rootBase = isset($rootBase) && is_string($rootBase) ? $rootBase : '../';
$navbarUserName = $garmentsUserName ?? 'Worker';
$navbarUserInitials = $garmentsUserInitials ?? 'W';
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
                <p class="navbar-kicker">Worker operations</p>
                <span class="navbar-title">Texwear Ltd</span>
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
                    id="workerNotificationMenu"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    aria-label="View notifications"
                >
                    <i class="bi bi-bell" aria-hidden="true"></i>
                    <span class="notification-count">3</span>
                </button>

                <div class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="workerNotificationMenu">
                    <div class="notification-menu__heading">
                        <span>Notifications</span>
                        <small>3 new</small>
                    </div>
                    <a class="notification-item" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>pages/worker_tasks.php">
                        <span class="notification-item__icon notification-item__icon--warning"><i class="bi bi-exclamation-triangle"></i></span>
                        <span><strong>New task assigned</strong><small>Binding and finishing task updated for ORD-2026-015.</small></span>
                    </a>
                    <a class="notification-item" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>pages/worker_production.php">
                        <span class="notification-item__icon notification-item__icon--primary"><i class="bi bi-diagram-3"></i></span>
                        <span><strong>Target increased</strong><small>Daily sewing target moved from 120 to 150 units.</small></span>
                    </a>
                    <a class="notification-item" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>pages/worker_attendance.php">
                        <span class="notification-item__icon notification-item__icon--success"><i class="bi bi-calendar-check"></i></span>
                        <span><strong>Shift updated</strong><small>Evening shift begins at 2:00 PM today.</small></span>
                    </a>
                </div>
            </div>

            <div class="navbar-user" aria-label="Logged in worker">
                <span class="user-avatar" aria-hidden="true"><?= htmlspecialchars($navbarUserInitials, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="navbar-user__details">
                    <strong><?= htmlspecialchars($navbarUserName, ENT_QUOTES, 'UTF-8'); ?></strong>
                    <small>Worker portal</small>
                </span>
            </div>

            <a class="btn btn-primary btn-sm navbar-logout" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>logout.php">
                <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                <span>Logout</span>
            </a>
        </div>
    </header>
