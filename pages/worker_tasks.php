<?php
/**
 * Worker tasks frontend prototype.
 * All values are local dummy data and intentionally static.
 */
$pageTitle = 'My Tasks';
$activePage = 'tasks';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$taskMetrics = [
    ['label' => 'Assigned Tasks', 'value' => '12', 'detail' => 'Across 4 production orders', 'icon' => 'bi-clipboard-check', 'tone' => 'primary'],
    ['label' => 'Completed', 'value' => '7', 'detail' => 'Finished this week', 'icon' => 'bi-check2-circle', 'tone' => 'success'],
    ['label' => 'Pending', 'value' => '5', 'detail' => 'Two deadlines are today', 'icon' => 'bi-hourglass-split', 'tone' => 'warning'],
];

$tasks = [
    ['id' => 'TSK-101', 'order' => 'ORD-2026-015', 'name' => 'Attach Collar', 'priority' => 'High', 'priorityKey' => 'high', 'priorityClass' => 'danger', 'deadline' => '12 Aug 2026', 'status' => 'In Progress', 'statusKey' => 'in-progress', 'statusClass' => 'primary'],
    ['id' => 'TSK-102', 'order' => 'ORD-2026-018', 'name' => 'Side Seam Assembly', 'priority' => 'Medium', 'priorityKey' => 'medium', 'priorityClass' => 'warning', 'deadline' => '13 Aug 2026', 'status' => 'Pending', 'statusKey' => 'pending', 'statusClass' => 'muted'],
    ['id' => 'TSK-103', 'order' => 'ORD-2026-021', 'name' => 'Hemming', 'priority' => 'Low', 'priorityKey' => 'low', 'priorityClass' => 'success', 'deadline' => '14 Aug 2026', 'status' => 'Completed', 'statusKey' => 'completed', 'statusClass' => 'success'],
    ['id' => 'TSK-104', 'order' => 'ORD-2026-027', 'name' => 'Pocket Set', 'priority' => 'High', 'priorityKey' => 'high', 'priorityClass' => 'danger', 'deadline' => '15 Aug 2026', 'status' => 'Pending', 'statusKey' => 'pending', 'statusClass' => 'muted'],
    ['id' => 'TSK-105', 'order' => 'ORD-2026-012', 'name' => 'Top Stitching', 'priority' => 'Medium', 'priorityKey' => 'medium', 'priorityClass' => 'warning', 'deadline' => '15 Aug 2026', 'status' => 'In Progress', 'statusKey' => 'in-progress', 'statusClass' => 'primary'],
    ['id' => 'TSK-106', 'order' => 'ORD-2026-031', 'name' => 'Button Fixing', 'priority' => 'Low', 'priorityKey' => 'low', 'priorityClass' => 'success', 'deadline' => '16 Aug 2026', 'status' => 'Completed', 'statusKey' => 'completed', 'statusClass' => 'success'],
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/worker_sidebar.php';
require_once __DIR__ . '/../includes/worker_navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="tasks-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><span>Home</span></li>
                        <li class="breadcrumb-item active" aria-current="page">My Tasks</li>
                    </ol>
                </nav>
                <h1 id="tasks-heading">My Tasks</h1>
                <p>Track assigned duties and production priorities for your active orders.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>New Task</span>
            </button>
        </section>

        <section aria-label="Task overview metrics">
            <div class="row g-3">
                <?php foreach ($taskMetrics as $metric): ?>
                    <div class="col-12 col-md-4">
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

        <section class="dashboard-section" aria-labelledby="tasks-table-heading">
            <article class="dashboard-card">
                <div class="dashboard-card__header module-card-heading">
                    <div>
                        <p class="section-eyebrow">Production queue</p>
                        <h2 id="tasks-table-heading">Assigned Tasks</h2>
                    </div>
                    <span class="card-period">6 task records</span>
                </div>

                <div class="module-toolbar">
                    <div class="module-toolbar__search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input class="form-control" type="search" placeholder="Search tasks or order IDs" aria-label="Search tasks" data-table-search="#workerTasksTable">
                    </div>
                    <div class="module-toolbar__filters">
                        <select class="form-select" aria-label="Filter tasks by status" data-table-filter="#workerTasksTable" data-filter-key="status">
                            <option value="all">All statuses</option>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                        <select class="form-select" aria-label="Filter tasks by priority" data-table-filter="#workerTasksTable" data-filter-key="priority">
                            <option value="all">All priorities</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive dashboard-table-wrap">
                    <table class="table dashboard-table align-middle mb-0" id="workerTasksTable" data-table-label="tasks">
                        <caption class="visually-hidden">Assigned worker tasks with priority, deadline, and status</caption>
                        <thead>
                            <tr>
                                <th scope="col">Task ID</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Task Name</th>
                                <th scope="col">Priority</th>
                                <th scope="col">Deadline</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr data-search-row data-status="<?= $escape($task['statusKey']); ?>" data-priority="<?= $escape($task['priorityKey']); ?>">
                                    <td><span class="table-id"><?= $escape($task['id']); ?></span></td>
                                    <td><span class="table-row-meta"><?= $escape($task['order']); ?></span></td>
                                    <td><strong><?= $escape($task['name']); ?></strong></td>
                                    <td><span class="status-badge status-badge--<?= $escape($task['priorityClass']); ?>"><?= $escape($task['priority']); ?></span></td>
                                    <td><?= $escape($task['deadline']); ?></td>
                                    <td><span class="status-badge status-badge--<?= $escape($task['statusClass']); ?>"><?= $escape($task['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr data-empty-state hidden>
                                <td colspan="6">
                                    <div class="table-empty-state">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                        <span>No tasks match the selected search or filters.</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <p data-table-count="#workerTasksTable">Showing 1–<?= count($tasks); ?> of <?= count($tasks); ?> tasks</p>
                    <nav aria-label="Tasks pagination">
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
