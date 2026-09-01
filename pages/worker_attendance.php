<?php
/**
 * Worker attendance frontend prototype.
 * All values are dummy data and static-only, without database usage.
 */
$pageTitle = 'Attendance';
$activePage = 'attendance';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$attendanceSummary = [
    ['label' => 'Present Days', 'value' => '24', 'detail' => 'Days worked this month', 'icon' => 'bi-calendar-check', 'tone' => 'success'],
    ['label' => 'Absent Days', 'value' => '2', 'detail' => 'Approved leave and missed days', 'icon' => 'bi-calendar-x', 'tone' => 'warning'],
    ['label' => 'Late Days', 'value' => '3', 'detail' => 'Late arrivals this month', 'icon' => 'bi-alarm', 'tone' => 'primary'],
    ['label' => 'Attendance %', 'value' => '92%', 'detail' => 'Current monthly attendance rate', 'icon' => 'bi-percent', 'tone' => 'indigo'],
];

$monthlyAttendance = [
    ['date' => '01 Aug 2026', 'checkIn' => '08:00 AM', 'checkOut' => '05:00 PM', 'status' => 'Present', 'statusClass' => 'success', 'statusKey' => 'present'],
    ['date' => '02 Aug 2026', 'checkIn' => '08:17 AM', 'checkOut' => '05:07 PM', 'status' => 'Late', 'statusClass' => 'warning', 'statusKey' => 'late'],
    ['date' => '03 Aug 2026', 'checkIn' => '08:00 AM', 'checkOut' => '05:00 PM', 'status' => 'Present', 'statusClass' => 'success', 'statusKey' => 'present'],
    ['date' => '04 Aug 2026', 'checkIn' => '—', 'checkOut' => '—', 'status' => 'Absent', 'statusClass' => 'muted', 'statusKey' => 'absent'],
    ['date' => '05 Aug 2026', 'checkIn' => '08:05 AM', 'checkOut' => '05:02 PM', 'status' => 'Present', 'statusClass' => 'success', 'statusKey' => 'present'],
    ['date' => '06 Aug 2026', 'checkIn' => '08:30 AM', 'checkOut' => '05:14 PM', 'status' => 'Late', 'statusClass' => 'warning', 'statusKey' => 'late'],
    ['date' => '07 Aug 2026', 'checkIn' => '08:00 AM', 'checkOut' => '05:00 PM', 'status' => 'Present', 'statusClass' => 'success', 'statusKey' => 'present'],
    ['date' => '08 Aug 2026', 'checkIn' => '08:00 AM', 'checkOut' => '05:00 PM', 'status' => 'Present', 'statusClass' => 'success', 'statusKey' => 'present'],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/worker_sidebar.php';
require_once __DIR__ . '/../includes/worker_navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="attendance-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><span>Home</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                    </ol>
                </nav>
                <h1 id="attendance-heading">Attendance</h1>
                <p>Review your monthly presence, punctuality, and shift compliance.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button">
                <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                <span>Mark Attendance</span>
            </button>
        </section>

        <section aria-label="Attendance summary cards">
            <div class="row g-3">
                <?php foreach ($attendanceSummary as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="attendance-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Monthly check-in</p>
                        <h2 id="attendance-table-heading">Monthly Attendance</h2>
                    </div>
                    <span class="card-period">August 2026</span>
                </div>

                <div class="attendance-chart-placeholder mb-4" aria-label="Monthly attendance chart placeholder">
                    <div class="chart-bars" aria-hidden="true">
                        <span style="height: 42%"></span>
                        <span style="height: 58%"></span>
                        <span style="height: 66%"></span>
                        <span style="height: 54%"></span>
                        <span style="height: 78%"></span>
                        <span style="height: 70%"></span>
                        <span style="height: 88%"></span>
                        <span style="height: 61%"></span>
                    </div>
                    <p>Attendance trend placeholder</p>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table align-middle mb-0">
                        <caption class="visually-hidden">Monthly attendance log with check-in, check-out, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Check In</th>
                                <th scope="col">Check Out</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($monthlyAttendance as $record): ?>
                                <tr>
                                    <td><?= $escape($record['date']); ?></td>
                                    <td><?= $escape($record['checkIn']); ?></td>
                                    <td><?= $escape($record['checkOut']); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($record['statusClass']); ?>"><?= $escape($record['status']); ?></span></td>
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
