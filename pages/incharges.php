<?php
/**
 * Incharges frontend prototype.
 * Static records reflect the Incharge entity, which is a specialized type of
 * the Employee entity, from the supplied schema and demo data.
 */
$pageTitle = 'Incharges';
$activePage = 'incharges';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$inchargeMetrics = [
    ['label' => 'Total Incharges', 'value' => '3', 'detail' => 'All incharge-position employee records', 'icon' => 'bi-person-video2', 'tone' => 'primary'],
    ['label' => 'Active Incharges', 'value' => '3', 'detail' => 'All incharges have an "Active" status', 'icon' => 'bi-person-check', 'tone' => 'success'],
    ['label' => 'Average Salary', 'value' => '৳61.0K', 'detail' => 'Mean salary for all incharges', 'icon' => 'bi-cash', 'tone' => 'teal'],
    ['label' => 'Operating Stages', 'value' => '3', 'detail' => 'Cutting, Sewing, and Finishing', 'icon' => 'bi-diagram-3', 'tone' => 'indigo'],
];

$incharges = [
    [
        'id' => '101', 'name' => 'Rahim Ahmed', 'operatingStage' => 'Cutting', 'stageKey' => 'cutting',
        'salary' => '60000', 'status' => 'Active', 'statusKey' => 'active', 'statusClass' => 'success',
        'address' => 'Dhaka', 'email' => 'rahim@garments.com', 'contact' => '01711111111', 'lastLogin' => '01 Aug 2026',
    ],
    [
        'id' => '102', 'name' => 'Karim Hasan', 'operatingStage' => 'Sewing', 'stageKey' => 'sewing',
        'salary' => '62000', 'status' => 'Active', 'statusKey' => 'active', 'statusClass' => 'success',
        'address' => 'Gazipur', 'email' => 'karim@garments.com', 'contact' => '01722222222', 'lastLogin' => '02 Aug 2026',
    ],
    [
        'id' => '103', 'name' => 'Sabbir Islam', 'operatingStage' => 'Finishing', 'stageKey' => 'finishing',
        'salary' => '61000', 'status' => 'Active', 'statusKey' => 'active', 'statusClass' => 'success',
        'address' => 'Narayanganj', 'email' => 'sabbir@garments.com', 'contact' => '01733333333', 'lastLogin' => '03 Aug 2026',
    ],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="incharges-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Incharges</li>
                    </ol>
                </nav>
                <h1 id="incharges-heading">Incharges</h1>
                <p>Manage employee profiles for all production stage incharges.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addInchargeModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Incharge</span>
            </button>
        </section>

        <section aria-label="Incharge overview">
            <div class="row g-3">
                <?php foreach ($inchargeMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="incharges-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Employee register</p>
                        <h2 id="incharges-table-heading">All Incharges</h2>
                    </div>
                    <span class="card-period">3 employee records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by name, email, or stage" aria-label="Search incharges" data-table-search="#inchargesTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter incharges by operating stage" data-table-filter="#inchargesTable" data-filter-key="stage">
                            <option value="all">All stages</option>
                            <option value="cutting">Cutting</option>
                            <option value="sewing">Sewing</option>
                            <option value="finishing">Finishing</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table incharges-table align-middle mb-0" id="inchargesTable" data-table-label="incharges">
                        <caption class="visually-hidden">Static incharge employee data with operating stage, salary, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Incharge ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Operating Stage</th>
                                <th scope="col">Salary</th>
                                <th scope="col">Status</th>
                                <th scope="col">Contact</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incharges as $incharge): ?>
                                <tr data-search-row data-stage="<?= $escape($incharge['stageKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($incharge['id']); ?></span></td>
                                    <td><strong><?= $escape($incharge['name']); ?></strong></td>
                                    <td><span class="table-row-meta"><?= $escape($incharge['operatingStage']); ?></span></td>
                                    <td>৳<?= $escape(number_format((float) $incharge['salary'])); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($incharge['statusClass']); ?>"><?= $escape($incharge['status']); ?></span></td>
                                    <td><a class="table-email" href="mailto:<?= $escape($incharge['email']); ?>"><?= $escape($incharge['email']); ?></a></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View <?= $escape($incharge['name']); ?>"
                                                aria-label="View <?= $escape($incharge['name']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewInchargeModal"
                                                data-incharge-id="#<?= $escape($incharge['id']); ?>"
                                                data-incharge-name="<?= $escape($incharge['name']); ?>"
                                                data-incharge-operating-stage="<?= $escape($incharge['operatingStage']); ?>"
                                                data-incharge-salary="৳<?= $escape(number_format((float) $incharge['salary'])); ?>"
                                                data-incharge-status="<?= $escape($incharge['status']); ?>"
                                                data-incharge-status-class="<?= $escape($incharge['statusClass']); ?>"
                                                data-incharge-address="<?= $escape($incharge['address']); ?>"
                                                data-incharge-email="<?= $escape($incharge['email']); ?>"
                                                data-incharge-contact="<?= $escape($incharge['contact']); ?>"
                                                data-incharge-last-login="<?= $escape($incharge['lastLogin']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit <?= $escape($incharge['name']); ?>" aria-label="Edit <?= $escape($incharge['name']); ?>" data-prototype-action="Edit <?= $escape($incharge['name']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button table-action-button--danger" type="button" title="Delete <?= $escape($incharge['name']); ?>" aria-label="Delete <?= $escape($incharge['name']); ?>" data-prototype-action="Delete <?= $escape($incharge['name']); ?>">
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="7">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No incharges match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#inchargesTable">Showing 1–<?= count($incharges); ?> of <?= count($incharges); ?> incharges</p>
                    <nav aria-label="Incharges pagination">
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

<!-- UI-only modal: Add Incharge -->
<div class="modal fade" id="addInchargeModal" tabindex="-1" aria-labelledby="addInchargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content app-modal">
            <form data-incharges-form>
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Employee management</p>
                        <h2 class="modal-title fs-5" id="addInchargeModalLabel">Add New Incharge</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inchargeId">Employee ID</label>
                            <input class="form-control" id="inchargeId" name="employee_id" type="number" min="1" placeholder="e.g. 104" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="inchargeName">Name</label>
                            <input class="form-control" id="inchargeName" name="name" type="text" placeholder="e.g. Anisul Haque" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="inchargePassword">Password</label>
                            <input class="form-control" id="inchargePassword" name="password" type="password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="inchargePosition">Position</label>
                            <input class="form-control" id="inchargePosition" name="position" type="text" value="Incharge" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="inchargeSalary">Salary</label>
                            <input class="form-control" id="inchargeSalary" name="salary" type="number" min="0" placeholder="e.g. 58000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="inchargeOperatingStage">Operating Stage</label>
                            <input class="form-control" id="inchargeOperatingStage" name="operating_stage" type="text" placeholder="e.g. Quality Check" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="inchargeEmail">Email</label>
                            <input class="form-control" id="inchargeEmail" name="email" type="email" placeholder="incharge@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="inchargeContact">Contact Number</label>
                            <input class="form-control" id="inchargeContact" name="contact_number" type="tel" placeholder="e.g. 01810 000004" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="inchargeAddress">Address</label>
                            <input class="form-control" id="inchargeAddress" name="address" type="text" placeholder="e.g. Savar, Dhaka" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Incharge</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewInchargeModal" tabindex="-1" aria-labelledby="viewInchargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Incharge profile</p>
                    <h2 class="modal-title fs-5" id="viewInchargeModalLabel"><span data-incharge-detail="name">Rahim Ahmed</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Employee Status</span>
                    <span class="status-badge status-badge--success" data-incharge-detail="status">Active</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Employee ID</dt><dd data-incharge-detail="id">#101</dd></div>
                    <div><dt>Operating Stage</dt><dd data-incharge-detail="operatingStage">Cutting</dd></div>
                    <div><dt>Salary</dt><dd data-incharge-detail="salary">৳60,000</dd></div>
                    <div><dt>Contact Number</dt><dd data-incharge-detail="contact">01711111111</dd></div>
                    <div class="detail-grid__wide"><dt>Email</dt><dd data-incharge-detail="email">rahim@garments.com</dd></div>
                    <div class="detail-grid__wide"><dt>Address</dt><dd data-incharge-detail="address">Dhaka</dd></div>
                    <div class="detail-grid__wide"><dt>Last Login</dt><dd data-incharge-detail="lastLogin">01 Aug 2026</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this incharge"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Incharge</button>
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