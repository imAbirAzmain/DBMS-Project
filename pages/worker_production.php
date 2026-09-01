<?php
/**
 * Worker production frontend prototype.
 * All values are dummy data and static-only, without database usage.
 */
$pageTitle = 'Production';
$activePage = 'production';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$productionSummary = [
    ['label' => 'Target Units', 'value' => '180', 'detail' => 'Daily sewing target', 'icon' => 'bi-bullseye', 'tone' => 'primary'],
    ['label' => 'Produced Units', 'value' => '145', 'detail' => 'Units completed today', 'icon' => 'bi-check2-circle', 'tone' => 'success'],
    ['label' => 'Remaining Units', 'value' => '35', 'detail' => 'Still left to complete', 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
    ['label' => 'Efficiency %', 'value' => '81%', 'detail' => 'Current throughput rate', 'icon' => 'bi-speedometer2', 'tone' => 'indigo'],
];

$history = [
    ['date' => '09 Aug 2026', 'order' => 'ORD-2026-015', 'stage' => 'Sewing', 'target' => '180', 'produced' => '145', 'efficiency' => '81%'],
    ['date' => '08 Aug 2026', 'order' => 'ORD-2026-018', 'stage' => 'Assembly', 'target' => '160', 'produced' => '128', 'efficiency' => '80%'],
    ['date' => '07 Aug 2026', 'order' => 'ORD-2026-021', 'stage' => 'Finishing', 'target' => '150', 'produced' => '126', 'efficiency' => '84%'],
    ['date' => '06 Aug 2026', 'order' => 'ORD-2026-027', 'stage' => 'Top Stitching', 'target' => '170', 'produced' => '139', 'efficiency' => '82%'],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/worker_sidebar.php';
require_once __DIR__ . '/../includes/worker_navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="production-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><span>Home</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Production</li>
                    </ol>
                </nav>
                <h1 id="production-heading">Production</h1>
                <p>Monitor your daily output and overall sewing efficiency against target.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Log Production</span>
            </button>
        </section>

        <section aria-label="Daily production summary">
            <div class="row g-3">
                <?php foreach ($productionSummary as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="production-history-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Daily tracking</p>
                        <h2 id="production-history-heading">Production History</h2>
                    </div>
                    <span class="card-period">Last 4 working days</span>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table align-middle mb-0">
                        <caption class="visually-hidden">Production records with date, orders, targets, produced units, and efficiency</caption>
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Order</th>
                                <th scope="col">Stage</th>
                                <th scope="col">Target</th>
                                <th scope="col">Produced</th>
                                <th scope="col">Efficiency</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $row): ?>
                                <tr>
                                    <td><?= $escape($row['date']); ?></td>
                                    <td><span class="table-id"><?= $escape($row['order']); ?></span></td>
                                    <td><?= $escape($row['stage']); ?></td>
                                    <td><?= $escape($row['target']); ?></td>
                                    <td><?= $escape($row['produced']); ?></td>
                                    <td>
                                        <div class="production-efficiency">
                                            <div class="progress stage-progress" role="progressbar" aria-label="Efficiency for <?= $escape($row['order']); ?>" aria-valuenow="<?= (int) str_replace('%', '', $row['efficiency']); ?>" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-fill--<?= (int) str_replace('%', '', $row['efficiency']); ?>"></div>
                                            </div>
                                            <span><?= $escape($row['efficiency']); ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
