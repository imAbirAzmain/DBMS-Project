<?php
$pageTitle = 'Login';
$assetBase = '../assets/';
$rootBase = '../';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> | Garments Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>css/style.css?v=2.1" rel="stylesheet">
</head>
<body class="login-page-body login-page-body--welcome">
    <main class="login-container">
        <!-- Welcome Card -->
        <div id="welcomeCard" class="welcome-card">
            <div class="login-brand">
                <span class="sidebar-brand__mark"><i class="bi bi-scissors" aria-hidden="true"></i></span>
                <span>
                    <strong>GarmentFlow</strong>
                    <small>Factory operations</small>
                </span>
            </div>
            <h1 class="welcome-title">Welcome to GarmentFlow</h1>
            <p class="welcome-subtitle">Factory Management System</p>

            <div class="welcome-options">
                <button type="button" class="option-card" data-role="incharge">
                    <span class="option-card__icon">👔</span>
                    <span class="option-card__title">Incharge</span>
                    <span class="option-card__subtitle">Supervisor / Manager</span>
                </button>
                <button type="button" class="option-card" data-role="worker">
                    <span class="option-card__icon">👷</span>
                    <span class="option-card__title">Worker</span>
                    <span class="option-card__subtitle">Attendance & Tasks</span>
                </button>
            </div>
        </div>

        <!-- Login Form Card -->
        <div id="loginFormCard" class="login-card" hidden>
            <div class="login-brand">
                <span class="sidebar-brand__mark"><i class="bi bi-scissors" aria-hidden="true"></i></span>
                <span>
                    <strong>GarmentFlow</strong>
                    <small>Factory operations</small>
                </span>
            </div>
            <h1 id="loginFormTitle" class="login-heading"></h1>
            <p class="login-subheading">Enter your credentials to access the dashboard.</p>

            <form id="loginForm" novalidate>
                <div class="mb-3">
                    <label for="employeeId" class="form-label">Username</label>
                    <input type="text" class="form-control" id="employeeId" name="employee_id" placeholder="e.g. rahim_ahmed" required>
                </div>
                <div class="mb-3 password-input-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <i class="bi bi-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <input type="hidden" id="positionInput" name="position">
                <a href="#" class="forgot-password-link">Forgot Password?</a>
                <div class="login-error" id="loginError" hidden>
                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                    <span>Invalid credentials. Please try again.</span>
                </div>
                <button type="submit" class="btn btn-primary w-100 login-button">Sign In</button>
                <button type="button" class="btn btn-link w-100 back-button" id="backToWelcome">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i> Back
                </button>
            </form>
            <p class="login-footer-text">© <?= date('Y'); ?> Garments Management System</p>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const welcomeCard = document.getElementById('welcomeCard');
            const loginFormCard = document.getElementById('loginFormCard');
            const loginFormTitle = document.getElementById('loginFormTitle');
            const loginForm = document.getElementById('loginForm');
            const loginError = document.getElementById('loginError');
            const positionInput = document.getElementById('positionInput');
            const backToWelcomeButton = document.getElementById('backToWelcome');
            const passwordInput = document.getElementById('password');
            const passwordToggleButton = document.querySelector('.password-toggle');

            // Function to show the login form for a specific role
            const showLoginForm = (role) => {
                welcomeCard.hidden = true;
                loginFormCard.hidden = false;
                loginFormTitle.textContent = role.charAt(0).toUpperCase() + role.slice(1) + ' Login';
                positionInput.value = role;
                document.body.classList.remove('login-page-body--welcome');
                document.body.classList.add('login-page-body--form');
                loginError.hidden = true; // Hide error on form switch
                loginForm.reset(); // Reset form fields
            };

            // Event listeners for option cards
            document.querySelector('[data-role="incharge"]').addEventListener('click', () => showLoginForm('incharge'));
            document.querySelector('[data-role="worker"]').addEventListener('click', () => showLoginForm('worker'));

            // Event listener for back button
            if (backToWelcomeButton) {
                backToWelcomeButton.addEventListener('click', () => {
                    welcomeCard.hidden = false;
                    loginFormCard.hidden = true;
                    document.body.classList.remove('login-page-body--form');
                    document.body.classList.add('login-page-body--welcome');
                    loginError.hidden = true; // Hide error on return
                    loginForm.reset(); // Reset form fields
                });
            }

            // Password visibility toggle
            if (passwordToggleButton && passwordInput) {
                passwordToggleButton.addEventListener('click', () => {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    passwordToggleButton.querySelector('i').classList.toggle('bi-eye');
                    passwordToggleButton.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }

            if (loginForm) {
                loginForm.addEventListener('submit', function (event) {
                    event.preventDefault();
                    loginError.hidden = true;

                    const employeeId = document.getElementById('employeeId').value;
                    const password = document.getElementById('password').value;
                    const position = positionInput.value; // Get value from hidden input

                    // Demo login credentials as requested
                    if (employeeId === 'rahim_ahmed' && password === '1234' && position === 'incharge') {
                        // On successful login, redirect to the dashboard
                        window.location.href = 'dashboard.php';
                    } else {
                        // Show an error message for invalid credentials
                        loginError.hidden = false;
                    }
                });
            }
        });
    </script>
</body>
</html>