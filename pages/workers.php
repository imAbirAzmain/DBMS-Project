<?php
require_once __DIR__ . '/../config/auth.php';

garments_session_start_safe();
if (!garments_current_user()) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = 'Workers';
$activePage = 'workers';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$workerMetrics = [
    ['label' => 'Total Workers', 'value' => '0', 'detail' => 'All worker-position employee records', 'icon' => 'bi-people-fill', 'tone' => 'primary'],
    ['label' => 'Active on Floor', 'value' => '0', 'detail' => 'Workers with an "Active" status', 'icon' => 'bi-person-check', 'tone' => 'success'],
    ['label' => 'Average Salary', 'value' => '৳0', 'detail' => 'Mean salary for all worker grades', 'icon' => 'bi-cash', 'tone' => 'teal'],
    ['label' => 'Top Grade', 'value' => 'Grade -', 'detail' => 'Highest assigned worker grade', 'icon' => 'bi-award', 'tone' => 'indigo'],
];

$workers = [
    [
        'id' => '201', 'name' => 'Jamal Hossain', 'grade' => 'A', 'gradeKey' => 'a', 'assignedStage' => 'Sewing',
        'salary' => '25000', 'status' => 'Active', 'statusKey' => 'active', 'statusClass' => 'success',
        'address' => 'Mirpur, Dhaka', 'email' => 'jamal.h@example.com', 'contact' => '01810 000001', 'lastLogin' => '06 Aug 2026',
    ],
    [
        'id' => '202', 'name' => 'Kamala Begum', 'grade' => 'B', 'gradeKey' => 'b', 'assignedStage' => 'Cutting',
        'salary' => '22000', 'status' => 'Active', 'statusKey' => 'active', 'statusClass' => 'success',
        'address' => 'Savar, Dhaka', 'email' => 'kamala.b@example.com', 'contact' => '01810 000002', 'lastLogin' => '05 Aug 2026',
    ],
    [
        'id' => '203', 'name' => 'Farid Miah', 'grade' => 'B', 'gradeKey' => 'b', 'assignedStage' => 'Finishing',
        'salary' => '23000', 'status' => 'On Leave', 'statusKey' => 'on-leave', 'statusClass' => 'warning',
        'address' => 'Gazipur', 'email' => 'farid.m@example.com', 'contact' => '01810 000003', 'lastLogin' => '01 Aug 2026',
    ],
    [
        'id' => '204', 'name' => 'Salma Khatun', 'grade' => 'A', 'gradeKey' => 'a', 'assignedStage' => 'Sewing',
        'salary' => '26000', 'status' => 'Active', 'statusKey' => 'active', 'statusClass' => 'success',
        'address' => 'Uttara, Dhaka', 'email' => 'salma.k@example.com', 'contact' => '01810 000004', 'lastLogin' => '06 Aug 2026',
    ],
    [
        'id' => '205', 'name' => 'Robiul Islam', 'grade' => 'C', 'gradeKey' => 'c', 'assignedStage' => 'Printing',
        'salary' => '20000', 'status' => 'Active', 'statusKey' => 'active', 'statusClass' => 'success',
        'address' => 'Tongi, Gazipur', 'email' => 'robiul.i@example.com', 'contact' => '01810 000005', 'lastLogin' => '04 Aug 2026',
    ],
    [
        'id' => '206', 'name' => 'Nasrin Akter', 'grade' => 'B', 'gradeKey' => 'b', 'assignedStage' => 'Quality Check',
        'salary' => '22500', 'status' => 'Inactive', 'statusKey' => 'inactive', 'statusClass' => 'muted',
        'address' => 'Savar, Dhaka', 'email' => 'nasrin.a@example.com', 'contact' => '01810 000006', 'lastLogin' => '30 Jul 2026',
    ],
];

$conn = garments_db_connect();
if ($conn) {
    $metricRow = garments_db_fetch_one("SELECT COUNT(*) AS total FROM Worker");
    if ($metricRow) {
        $workerMetrics[0]['value'] = (string) (int) ($metricRow['TOTAL'] ?? 0);
    }

    $activeRow = garments_db_fetch_one("SELECT COUNT(*) AS total FROM Worker w JOIN Employee e ON e.Employee_ID = w.Employee_ID WHERE e.Status = 'Active'");
    if ($activeRow) {
        $workerMetrics[1]['value'] = (string) (int) ($activeRow['TOTAL'] ?? 0);
    }

    $avgRow = garments_db_fetch_one("SELECT ROUND(AVG(Salary), 2) AS avg_salary FROM Employee WHERE Position = 'Worker'");
    if ($avgRow) {
        $workerMetrics[2]['value'] = '৳' . number_format((float) ($avgRow['AVG_SALARY'] ?? 0), 2, '.', ',');
    }

    $topGradeRow = garments_db_fetch_one("SELECT Grade FROM Worker ORDER BY CASE Grade WHEN 'A' THEN 3 WHEN 'B' THEN 2 WHEN 'C' THEN 1 ELSE 0 END DESC, Employee_ID ");
    if ($topGradeRow) {
        $grade = strtoupper((string) ($topGradeRow['GRADE'] ?? ''));
        $workerMetrics[3]['value'] = $grade ? 'Grade ' . $grade : 'Grade -';
    }

    $workerQuery = "
        SELECT
            w.Employee_ID AS id,
            w.Name AS name,
            w.Grade AS grade,
            LOWER(w.Grade) AS grade_key,
            COALESCE(ps.Stage_Name, 'Unassigned') AS assigned_stage,
            e.Salary AS salary,
            e.Status AS status,
            LOWER(e.Status) AS status_key,
            w.Address AS address,
            w.Email AS email,
            COALESCE((SELECT wc.Contact_Number FROM Worker_Contact wc WHERE wc.Employee_ID = w.Employee_ID AND ROWNUM = 1), 'N/A') AS contact,
            e.Last_Login AS last_login
        FROM Worker w
        JOIN Employee e ON e.Employee_ID = w.Employee_ID
        LEFT JOIN Rel_Worker_ProductionStage rwp ON rwp.Employee_ID = w.Employee_ID
        LEFT JOIN Production_Stage ps ON ps.Stage_ID = rwp.Stage_ID
        ORDER BY w.Employee_ID
    ";

    $dbWorkers = garments_db_fetch_all($workerQuery);
    if (!empty($dbWorkers)) {
        $workers = [];
        foreach ($dbWorkers as $row) {
            $status = strtoupper((string) ($row['STATUS'] ?? ''));
            $statusKey = strtolower((string) ($row['STATUS_KEY'] ?? 'active'));
            $statusClass = in_array($statusKey, ['active'], true) ? 'success' : (in_array($statusKey, ['on leave', 'on-leave'], true) ? 'warning' : 'muted');
            $grade = strtoupper((string) ($row['GRADE'] ?? ''));
            $workers[] = [
                'id' => (string) ($row['ID'] ?? ''),
                'name' => (string) ($row['NAME'] ?? ''),
                'grade' => $grade,
                'gradeKey' => strtolower($grade),
                'assignedStage' => (string) ($row['ASSIGNED_STAGE'] ?? 'Unassigned'),
                'salary' => (string) number_format((float) ($row['SALARY'] ?? 0), 2, '.', ''),
                'status' => $status !== '' ? $status : 'Active',
                'statusKey' => $statusKey,
                'statusClass' => $statusClass,
                'address' => (string) ($row['ADDRESS'] ?? 'N/A'),
                'email' => (string) ($row['EMAIL'] ?? 'N/A'),
                'contact' => (string) ($row['CONTACT'] ?? 'N/A'),
                'lastLogin' => $row['LAST_LOGIN'] ? date('d M Y', strtotime((string) $row['LAST_LOGIN'])) : 'N/A',
            ];
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="workers-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Workers</li>
                    </ol>
                </nav>
                <h1 id="workers-heading">Workers</h1>
                <p>Manage employee profiles for all production floor workers.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button" data-bs-toggle="modal" data-bs-target="#addWorkerModal">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Add Worker</span>
            </button>
        </section>

        <section aria-label="Worker overview">
            <div class="row g-3">
                <?php foreach ($workerMetrics as $metric): ?>
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

        <section class="dashboard-section" aria-labelledby="workers-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Employee register</p>
                        <h2 id="workers-table-heading">All Workers</h2>
                    </div>
                    <span class="card-period">6 employee records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search by name, email, or stage" aria-label="Search workers" data-table-search="#workersTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter workers by grade" data-table-filter="#workersTable" data-filter-key="grade">
                            <option value="all">All grades</option>
                            <option value="a">Grade A</option>
                            <option value="b">Grade B</option>
                            <option value="c">Grade C</option>
                        </select>
                        <select class="form-select" aria-label="Filter workers by status" data-table-filter="#workersTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="active">Active</option>
                            <option value="on-leave">On Leave</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table workers-table align-middle mb-0" id="workersTable" data-table-label="workers">
                        <caption class="visually-hidden">Static worker employee data with grade, stage, salary, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Worker ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Assigned Stage</th>
                                <th scope="col">Salary</th>
                                <th scope="col">Status</th>
                                <th scope="col">Contact</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workers as $worker): ?>
                                <tr data-search-row data-grade="<?= $escape($worker['gradeKey']); ?>" data-status="<?= $escape($worker['statusKey']); ?>">
                                    <td><span class="table-id">#<?= $escape($worker['id']); ?></span></td>
                                    <td><strong><?= $escape($worker['name']); ?></strong></td>
                                    <td><span class="grade-badge">Grade <?= $escape($worker['grade']); ?></span></td>
                                    <td><span class="table-row-meta"><?= $escape($worker['assignedStage']); ?></span></td>
                                    <td>৳<?= $escape(number_format((float) $worker['salary'])); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($worker['statusClass']); ?>"><?= $escape($worker['status']); ?></span></td>
                                    <td><a class="table-email" href="mailto:<?= $escape($worker['email']); ?>"><?= $escape($worker['email']); ?></a></td>
                                    <td>
                                        <div class="table-action-buttons">
                                            <button
                                                class="table-action-button"
                                                type="button"
                                                title="View <?= $escape($worker['name']); ?>"
                                                aria-label="View <?= $escape($worker['name']); ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewWorkerModal"
                                                data-worker-id="#<?= $escape($worker['id']); ?>"
                                                data-worker-name="<?= $escape($worker['name']); ?>"
                                                data-worker-grade="Grade <?= $escape($worker['grade']); ?>"
                                                data-worker-assigned-stage="<?= $escape($worker['assignedStage']); ?>"
                                                data-worker-salary="৳<?= $escape(number_format((float) $worker['salary'])); ?>"
                                                data-worker-status="<?= $escape($worker['status']); ?>"
                                                data-worker-status-class="<?= $escape($worker['statusClass']); ?>"
                                                data-worker-address="<?= $escape($worker['address']); ?>"
                                                data-worker-email="<?= $escape($worker['email']); ?>"
                                                data-worker-contact="<?= $escape($worker['contact']); ?>"
                                                data-worker-last-login="<?= $escape($worker['lastLogin']); ?>"
                                            >
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button" type="button" title="Edit <?= $escape($worker['name']); ?>" aria-label="Edit <?= $escape($worker['name']); ?>" data-prototype-action="Edit <?= $escape($worker['name']); ?>">
                                                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            </button>
                                            <button class="table-action-button table-action-button--danger" type="button" title="Delete <?= $escape($worker['name']); ?>" aria-label="Delete <?= $escape($worker['name']); ?>" data-prototype-action="Delete <?= $escape($worker['name']); ?>">
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="8">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No workers match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#workersTable">Showing 1–<?= count($workers); ?> of <?= count($workers); ?> workers</p>
                    <nav aria-label="Workers pagination">
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

<!-- UI-only modal: Add Worker -->
<div class="modal fade" id="addWorkerModal" tabindex="-1" aria-labelledby="addWorkerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content app-modal">
<form data-workers-form data-backend-resource="worker">
                <div class="modal-header">
                    <div>
                        <p class="section-eyebrow">Employee management</p>
                        <h2 class="modal-title fs-5" id="addWorkerModalLabel">Add New Worker</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="modal-intro">This frontend-only form does not save or change records.</p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="workerId">Employee ID</label>
                            <input class="form-control" id="workerId" name="employee_id" type="number" min="1" placeholder="e.g. 204" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label" for="workerName">Name</label>
                            <input class="form-control" id="workerName" name="name" type="text" placeholder="e.g. Salma Akter" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="workerPassword">Password</label>
                            <input class="form-control" id="workerPassword" name="password" type="password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="workerPosition">Position</label>
                            <input class="form-control" id="workerPosition" name="position" type="text" value="Worker" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="workerSalary">Salary</label>
                            <input class="form-control" id="workerSalary" name="salary" type="number" min="0" placeholder="e.g. 21000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="workerGrade">Grade</label>
                            <select class="form-select" id="workerGrade" name="grade" required>
                                <option value="" selected disabled>Select grade</option>
                                <option value="A">Grade A</option>
                                <option value="B">Grade B</option>
                                <option value="C">Grade C</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="workerStatus">Status</label>
                            <select class="form-select" id="workerStatus" name="status" required>
                                <option value="" selected disabled>Select status</option>
                                <option value="active">Active</option>
                                <option value="on-leave">On Leave</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="workerEmail">Email</label>
                            <input class="form-control" id="workerEmail" name="email" type="email" placeholder="worker@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="workerContact">Contact Number</label>
                            <input class="form-control" id="workerContact" name="contact_number" type="tel" placeholder="e.g. 01810 000004" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="workerAddress">Address</label>
                            <input class="form-control" id="workerAddress" name="address" type="text" placeholder="e.g. Uttara, Dhaka" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add Worker</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail modal is populated from the selected static table row. -->
<div class="modal fade" id="viewWorkerModal" tabindex="-1" aria-labelledby="viewWorkerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content app-modal">
            <div class="modal-header">
                <div>
                    <p class="section-eyebrow">Worker profile</p>
                    <h2 class="modal-title fs-5" id="viewWorkerModalLabel"><span data-worker-detail="name">Jamal Hossain</span></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-status">
                    <span>Employee Status</span>
                    <span class="status-badge status-badge--success" data-worker-detail="status">Active</span>
                </div>
                <dl class="detail-grid">
                    <div><dt>Employee ID</dt><dd data-worker-detail="id">#201</dd></div>
                    <div><dt>Grade</dt><dd data-worker-detail="grade">Grade A</dd></div>
                    <div><dt>Salary</dt><dd data-worker-detail="salary">৳25,000</dd></div>
                    <div><dt>Assigned Stage</dt><dd data-worker-detail="assignedStage">Sewing</dd></div>
                    <div><dt>Contact Number</dt><dd data-worker-detail="contact">01810 000001</dd></div>
                    <div class="detail-grid__wide"><dt>Email</dt><dd data-worker-detail="email">jamal.h@example.com</dd></div>
                    <div class="detail-grid__wide"><dt>Address</dt><dd data-worker-detail="address">Mirpur, Dhaka</dd></div>
                    <div class="detail-grid__wide"><dt>Last Login</dt><dd data-worker-detail="lastLogin">06 Aug 2026</dd></div>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="button" data-prototype-action="Edit this worker"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Worker</button>
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
