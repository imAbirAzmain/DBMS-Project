<?php
/**
 * Worker portal sidebar. $activePage is supplied by each worker page so the
 * current destination is always clearly identified.
 */
$activePage = isset($activePage) && is_string($activePage) ? $activePage : 'dashboard';
$pageBase = isset($pageBase) && is_string($pageBase) ? $pageBase : '';
$rootBase = isset($rootBase) && is_string($rootBase) ? $rootBase : '../';
$sidebarUserName = $garmentsUserName ?? 'Worker';
$sidebarUserInitials = $garmentsUserInitials ?? 'W';

$workerSidebarItems = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-1x2-fill', 'path' => 'worker_dashboard.php'],
    ['key' => 'tasks', 'label' => 'My Tasks', 'icon' => 'bi-clipboard-check', 'path' => 'worker_tasks.php'],
    ['key' => 'production', 'label' => 'Production', 'icon' => 'bi-diagram-3', 'path' => 'worker_production.php'],
    ['key' => 'attendance', 'label' => 'Attendance', 'icon' => 'bi-calendar-check', 'path' => 'worker_attendance.php'],
    ['key' => 'notifications', 'label' => 'Notifications', 'icon' => 'bi-bell', 'path' => 'worker_notifications.php'],
    ['key' => 'profile', 'label' => 'Profile', 'icon' => 'bi-person-circle', 'path' => 'worker_profile.php'],
    ['key' => 'logout', 'label' => 'Logout', 'icon' => 'bi-box-arrow-right', 'path' => '../logout.php'],
];
?>
<aside class="app-sidebar" id="appSidebar" aria-label="Worker portal navigation">
    <div class="sidebar-brand">
        <a class="sidebar-brand__link" href="<?= htmlspecialchars($pageBase, ENT_QUOTES, 'UTF-8'); ?>worker_dashboard.php" aria-label="Texwear Ltd worker dashboard">
            <span class="sidebar-brand__mark"><i class="bi bi-scissors" aria-hidden="true"></i></span>
            <span>
                <strong>Texwear Ltd</strong>
                <small>Worker portal</small>
            </span>
        </a>
        <button class="sidebar-close" type="button" aria-label="Close navigation menu" data-sidebar-close>
            <i class="bi bi-x-lg" aria-hidden="true"></i>
        </button>
    </div>

    <div class="sidebar-scroll">
        <p class="sidebar-section-label">Main menu</p>
        <nav>
            <ul class="sidebar-nav">
                <?php foreach ($workerSidebarItems as $item): ?>
                    <?php
                    $isActive = $activePage === $item['key'];
                    $itemUrl = strpos($item['path'], '../') === 0 ? $item['path'] : ($pageBase . $item['path']);
                    ?>
                    <li>
                        <a class="sidebar-nav__link<?= $isActive ? ' is-active' : ''; ?>" href="<?= htmlspecialchars($itemUrl, ENT_QUOTES, 'UTF-8'); ?>"<?= $isActive ? ' aria-current="page"' : ''; ?>>
                            <i class="bi <?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
                            <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php if ($isActive): ?><i class="bi bi-chevron-right sidebar-nav__active-icon" aria-hidden="true"></i><?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <span class="sidebar-user__avatar" aria-hidden="true"><?= htmlspecialchars($sidebarUserInitials, ENT_QUOTES, 'UTF-8'); ?></span>
            <span>
                <strong><?= htmlspecialchars($sidebarUserName, ENT_QUOTES, 'UTF-8'); ?></strong>
                <small>Worker portal</small>
            </span>
        </div>
        <a class="sidebar-logout" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>logout.php">
            <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
<div class="sidebar-backdrop" data-sidebar-close aria-hidden="true"></div>
