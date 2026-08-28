<?php
$role = $_GET['role'] ?? 'incharge';
$normalizedRole = in_array($role, ['incharge', 'worker'], true) ? $role : 'incharge';
$pageTitle = $normalizedRole === 'worker' ? 'Worker Login' : 'Incharge Login';
$assetBase = 'assets/';
$brandName = 'Texwear Ltd';
$identityLabel = $normalizedRole === 'worker' ? 'Employee ID' : 'Username';
$identityPlaceholder = $normalizedRole === 'worker' ? 'e.g. WRK-001' : 'e.g. rahim_ahmed';
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

            <form id="authForm" class="auth-form" novalidate>
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

                <input type="hidden" id="portalRole" value="<?= htmlspecialchars($normalizedRole, ENT_QUOTES, 'UTF-8'); ?>">

                <div class="auth-form__meta">
                    <label class="check-inline">
                        <input type="checkbox" name="remember" value="1">
                        <span>Remember me</span>
                    </label>
                    <a href="#">Forgot password?</a>
                </div>

                <div class="login-error" id="loginError" hidden>
                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                    <span>Invalid credentials. Please try again.</span>
                </div>

                <button type="submit" class="btn btn-primary w-100 login-button">Sign in</button>
            </form>

            <div class="demo-box">
                <p>Demo credentials</p>
                <div class="demo-box__row">
                    <span><?= $normalizedRole === 'worker' ? 'Worker ID' : 'Username'; ?></span>
                    <strong><?= $normalizedRole === 'worker' ? 'WRK-001' : 'rahim_ahmed'; ?></strong>
                </div>
                <div class="demo-box__row">
                    <span>Password</span>
                    <strong><?= $normalizedRole === 'worker' ? 'worker123' : '1234'; ?></strong>
                </div>
            </div>
        </section>
    </main>

    <div class="auth-footer">© <?= date('Y'); ?> Texwear Ltd</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('authForm');
            const roleField = document.getElementById('portalRole');
            const identityField = document.getElementById('identity');
            const passwordField = document.getElementById('password');
            const loginError = document.getElementById('loginError');
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

            if (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    loginError.hidden = true;

                    const role = roleField ? roleField.value : 'incharge';
                    const identity = identityField ? identityField.value.trim() : '';
                    const password = passwordField ? passwordField.value : '';

                    const isWorkerMatch = role === 'worker' && identity === 'WRK-001' && password === 'worker123';
                    const isInchargeMatch = role === 'incharge' && identity === 'rahim_ahmed' && password === '1234';

                    if (isWorkerMatch) {
                        window.location.href = 'pages/worker_dashboard.php';
                        return;
                    }

                    if (isInchargeMatch) {
                        window.location.href = 'pages/dashboard.php';
                        return;
                    }

                    loginError.hidden = false;
                });
            }
        });
    </script>
</body>
</html>
