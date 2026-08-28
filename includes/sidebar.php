<?php
/**
 * Shared primary navigation. $activePage is supplied by each page so the
 * current destination is always clearly identified.
 */
$activePage = isset($activePage) && is_string($activePage) ? $activePage : 'dashboard';
$pageBase = isset($pageBase) && is_string($pageBase) ? $pageBase : '';
$rootBase = isset($rootBase) && is_string($rootBase) ? $rootBase : '../';

$sidebarItems = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-1x2-fill', 'path' => 'dashboard.php'],
    ['key' => 'orders', 'label' => 'Orders', 'icon' => 'bi-clipboard2-check', 'path' => 'orders.php'],
    ['key' => 'order-styles', 'label' => 'Order Styles', 'icon' => 'bi-palette2', 'path' => 'order_styles.php'],
    ['key' => 'production', 'label' => 'Production', 'icon' => 'bi-diagram-3', 'path' => 'production.php'],
    ['key' => 'workers', 'label' => 'Workers', 'icon' => 'bi-people', 'path' => 'workers.php'],
    ['key' => 'incharges', 'label' => 'Incharges', 'icon' => 'bi-person-gear', 'path' => 'incharges.php'],
    ['key' => 'materials', 'label' => 'Materials', 'icon' => 'bi-boxes', 'path' => 'materials.php'],
    ['key' => 'machinery', 'label' => 'Machinery', 'icon' => 'bi-gear-wide-connected', 'path' => 'machinery.php'],
    ['key' => 'inspection', 'label' => 'Inspection', 'icon' => 'bi-patch-check', 'path' => 'inspection.php'],
    ['key' => 'final-products', 'label' => 'Final Products', 'icon' => 'bi-box-seam', 'path' => 'final_products.php'],
    ['key' => 'packaging', 'label' => 'Packaging', 'icon' => 'bi-box2-heart', 'path' => 'packaging.php'],
    ['key' => 'shipment', 'label' => 'Shipment', 'icon' => 'bi-truck', 'path' => 'shipment.php'],
    ['key' => 'buyers', 'label' => 'Buyers', 'icon' => 'bi-buildings', 'path' => 'buyers.php'],
    ['key' => 'suppliers', 'label' => 'Suppliers', 'icon' => 'bi-truck-flatbed', 'path' => 'suppliers.php'],
    ['key' => 'payments', 'label' => 'Payments', 'icon' => 'bi-credit-card', 'path' => 'payments.php'],
    ['key' => 'accounts', 'label' => 'Accounts', 'icon' => 'bi-bank', 'path' => 'accounts.php'],
    ['key' => 'bom', 'label' => 'BOM', 'icon' => 'bi-receipt-cutoff', 'path' => 'bom.php'],
];
?>
<aside class="app-sidebar" id="appSidebar" aria-label="Primary navigation">
    <div class="sidebar-brand">
        <a class="sidebar-brand__link" href="<?= htmlspecialchars($pageBase, ENT_QUOTES, 'UTF-8'); ?>dashboard.php" aria-label="Texwear Ltd dashboard">
            <span class="sidebar-brand__mark"><i class="bi bi-scissors" aria-hidden="true"></i></span>
            <span>
                <strong>Texwear Ltd</strong>
                <small>Factory operations</small>
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
                <?php foreach ($sidebarItems as $item): ?>
                    <?php
                    $isActive = $activePage === $item['key'];
                    $itemUrl = $pageBase . $item['path'];
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
            <span class="sidebar-user__avatar">RA</span>
            <span>
                <strong>Rahim Ahmed</strong>
                <small>Factory Admin</small>
            </span>
        </div>
        <a class="sidebar-logout" href="<?= htmlspecialchars($rootBase, ENT_QUOTES, 'UTF-8'); ?>logout.php">
            <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
<div class="sidebar-backdrop" data-sidebar-close aria-hidden="true"></div>
