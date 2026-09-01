<?php
/**
 * Worker profile frontend prototype.
 * All values are dummy data and static-only, without database usage.
 */
$pageTitle = 'Profile';
$activePage = 'profile';
$assetBase = '../assets/';
$pageBase = '';
$rootBase = '../';

$escape = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$worker = [
    'employeeId' => 'WRK-001',
    'name' => 'Rahim Ahmed',
    'phone' => '+880 1712-345678',
    'address' => 'House 21, Road 14, Dhanmondi, Dhaka',
    'department' => 'Sewing',
    'joiningDate' => '12 Jan 2024',
    'supervisor' => 'Karim Uddin',
    'shift' => 'Day Shift',
    'skills' => ['Sewing', 'Cutting', 'Finishing'],
    'completion' => 82,
];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/worker_sidebar.php';
require_once __DIR__ . '/../includes/worker_navbar.php';
?>

<main class="app-main" id="main-content" tabindex="-1">
    <div class="container-fluid dashboard-container">
        <section class="page-hero" aria-labelledby="profile-heading">
            <div>
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb app-breadcrumb mb-2">
                        <li class="breadcrumb-item"><span>Home</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
                <h1 id="profile-heading">Profile</h1>
                <p>Review your personal information, work details, and skill profile.</p>
            </div>
            <button class="btn btn-primary page-hero__action" type="button">
                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                <span>Edit Profile</span>
            </button>
        </section>

        <section class="row g-4 dashboard-section" aria-label="Worker profile information">
            <div class="col-12 col-xl-8">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Identity</p>
                            <h2>Personal Information</h2>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="profile-field">
                                <span>Employee ID</span>
                                <strong><?= $escape($worker['employeeId']); ?></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-field">
                                <span>Name</span>
                                <strong><?= $escape($worker['name']); ?></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-field">
                                <span>Phone</span>
                                <strong><?= $escape($worker['phone']); ?></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-field">
                                <span>Address</span>
                                <strong><?= $escape($worker['address']); ?></strong>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-4">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Work status</p>
                            <h2>Employment Information</h2>
                        </div>
                    </div>

                    <div class="stacked-list">
                        <div class="stacked-list__item">
                            <span>Department</span>
                            <strong><?= $escape($worker['department']); ?></strong>
                        </div>
                        <div class="stacked-list__item">
                            <span>Joining Date</span>
                            <strong><?= $escape($worker['joiningDate']); ?></strong>
                        </div>
                        <div class="stacked-list__item">
                            <span>Supervisor</span>
                            <strong><?= $escape($worker['supervisor']); ?></strong>
                        </div>
                        <div class="stacked-list__item">
                            <span>Shift</span>
                            <strong><?= $escape($worker['shift']); ?></strong>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="row g-4 dashboard-section" aria-label="Skills and profile completion">
            <div class="col-12 col-xl-7">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Capabilities</p>
                            <h2>Skills</h2>
                        </div>
                    </div>

                    <div class="skill-list">
                        <?php foreach ($worker['skills'] as $skill): ?>
                            <span class="skill-badge"><?= $escape($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-5">
                <article class="dashboard-card h-100">
                    <div class="dashboard-card__header">
                        <div>
                            <p class="section-eyebrow">Progress</p>
                            <h2>Profile Completion</h2>
                        </div>
                    </div>

                    <div class="profile-progress">
                        <div class="profile-progress__header">
                            <strong><?= $escape($worker['completion']); ?>%</strong>
                            <span>Completed</span>
                        </div>
                        <div class="progress stage-progress" role="progressbar" aria-label="Profile completion" aria-valuenow="<?= $escape($worker['completion']); ?>" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-fill--<?= $escape($worker['completion']); ?>"></div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
