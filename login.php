<?php
require_once __DIR__ . '/config/auth.php';

$role = $_GET['role'] ?? 'incharge';
$normalizedRole = in_array($role, ['incharge', 'worker'], true) ? $role : 'incharge';
$loginError = isset($_GET['error']) && $_GET['error'] === 'invalid';
$loginUnauthorized = isset($_GET['error']) && $_GET['error'] === 'unauthorized';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = trim((string) ($_POST['identity'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $requestedRole = trim((string) ($_POST['role'] ?? $normalizedRole));
    $user = garments_authenticate_employee($identity, $password, $requestedRole);

    if ($user) {
        garments_login_user($user);
        $redirect = strtolower($requestedRole) === 'worker' ? 'pages/worker_dashboard.php' : 'pages/dashboard.php';
        header('Location: ' . $redirect);
        exit;
    }

    header('Location: login.php?role=' . urlencode($requestedRole) . '&error=invalid');
    exit;
}

$pageTitle = $normalizedRole === 'worker' ? 'Worker Login' : 'Incharge Login';
$assetBase = 'assets/';
$brandName = 'Texwear Ltd';
$identityLabel = $normalizedRole === 'worker' ? 'Employee ID' : 'Employee ID';
$identityPlaceholder = $normalizedRole === 'worker' ? 'e.g. 201' : 'e.g. 101';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Texwear Ltd secure portal login">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> | Texwear Ltd</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>css/style.css?v=3.0" rel="stylesheet">
</head>
<body class="auth-page-body">
    <main class="auth-shell">
        <section class="auth-hero" aria-label="Texwear corporate overview">
            <div class="auth-brand">
                <span class="brand-mark__icon"><i class="bi bi-scissors" aria-hidden="true"></i></span>
                <div>
                    <strong><?= htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8'); ?></strong>
                    <small>Garments management</small>
                </div>
            </div>

            <div class="auth-copy">
                <span class="eyebrow">Protected access</span>
                <h1>Access the operations portal for your team.</h1>
                <p>Use the portal matching your role to view production performance, manage daily workloads, and monitor factory activity.</p>
            </div>

            <div class="auth-role-switch" aria-label="Portal selector">
                <a href="login.php?role=incharge" class="role-switch <?= $normalizedRole === 'incharge' ? 'is-active' : ''; ?>">Incharge</a>
                <a href="login.php?role=worker" class="role-switch <?= $normalizedRole === 'worker' ? 'is-active' : ''; ?>">Worker</a>
            </div>
        </section>

        <section class="auth-panel" aria-label="Login form">
            <div class="auth-panel__header">
                <p class="eyebrow eyebrow--dark">Portal login</p>
                <h2><?= $normalizedRole === 'worker' ? 'Worker portal' : 'Incharge portal'; ?></h2>
            </div>

            <form id="authForm" class="auth-form" method="post" action="login.php" novalidate>
                <div class="mb-3">
                    <label for="identity" class="form-label"><?= htmlspecialchars($identityLabel, ENT_QUOTES, 'UTF-8'); ?></label>
                    <input type="text" class="form-control" id="identity" name="identity" placeholder="<?= htmlspecialchars($identityPlaceholder, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3 password-input-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <i class="bi bi-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>

                <input type="hidden" id="portalRole" name="role" value="<?= htmlspecialchars($normalizedRole, ENT_QUOTES, 'UTF-8'); ?>">

                <div class="auth-form__meta">
                    <label class="check-inline">
                        <input type="checkbox" name="remember" value="1">
                        <span>Remember me</span>
                    </label>
                    <a href="#">Forgot password?</a>
                </div>

                <div class="login-error" id="loginError" <?= $loginError || $loginUnauthorized ? '' : 'hidden'; ?>>
                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                    <span><?= $loginUnauthorized ? 'You do not have access to this portal.' : 'Invalid credentials. Please try again.'; ?></span>
                </div>

                <button type="submit" class="btn btn-primary w-100 login-button">Sign in</button>
            </form>

            <div class="demo-box">
                <p>Oracle-backed sample credentials</p>
                <div class="demo-box__row">
                    <span><?= $normalizedRole === 'worker' ? 'Worker ID' : 'Incharge ID'; ?></span>
                    <strong><?= $normalizedRole === 'worker' ? '201' : '101'; ?></strong>
                </div>
                <div class="demo-box__row">
                    <span>Password</span>
                    <strong><?= $normalizedRole === 'worker' ? 'pass201' : 'pass101'; ?></strong>
                </div>
            </div>
        </section>
    </main>

    <div class="auth-footer">© <?= date('Y'); ?> Texwear Ltd</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordField = document.getElementById('password');
            const passwordToggle = document.querySelector('.password-toggle');

            if (passwordToggle && passwordField) {
                passwordToggle.addEventListener('click', function () {
                    const isPassword = passwordField.getAttribute('type') === 'password';
                    passwordField.setAttribute('type', isPassword ? 'text' : 'password');
                    const icon = passwordToggle.querySelector('i');
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                });
            }
        });
    </script>
</body>
</html>
