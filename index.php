<?php
$pageTitle = 'Welcome';
$assetBase = 'assets/';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Texwear Ltd garments management portal">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> | Texwear Ltd</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>css/style.css?v=3.0" rel="stylesheet">
</head>
<body class="landing-page-body">
    <header class="landing-header">
        <div class="container landing-header__inner">
            <div class="brand-mark">
                <span class="brand-mark__icon"><i class="bi bi-scissors" aria-hidden="true"></i></span>
                <div>
                    <strong>Texwear Ltd</strong>
                    <small>Garments management</small>
                </div>
            </div>
            <nav class="landing-nav" aria-label="Main navigation">
                <a href="#solutions">Solutions</a>
                <a href="#operations">Operations</a>
                <a href="#portal">Portal</a>
            </nav>
        </div>
    </header>

    <main class="landing-main">
        <section class="container landing-hero" id="portal">
            <div class="landing-copy">
                <span class="eyebrow">Factory operations platform</span>
                <h1>Smart production control for every stage of your garments business.</h1>
                <p>Coordinate orders, production, quality, attendance, and delivery workflows from a single operational dashboard built for apparel manufacturing teams.</p>

                <div class="landing-stats" aria-label="Business highlights">
                    <div>
                        <strong>6</strong>
                        <span>Active orders</span>
                    </div>
                    <div>
                        <strong>96%</strong>
                        <span>On-time output</span>
                    </div>
                    <div>
                        <strong>24/7</strong>
                        <span>Team visibility</span>
                    </div>
                </div>
            </div>

            <div class="portal-panel" aria-label="Portal selection">
                <p class="portal-panel__label">Choose your portal</p>
                <div class="portal-grid">
                    <a class="portal-card portal-card--incharge" href="login.php?role=incharge">
                        <span class="portal-card__icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
                        <span class="portal-card__title">Incharge Portal</span>
                        <span class="portal-card__meta">Supervisors & managers</span>
                    </a>
                    <a class="portal-card portal-card--worker" href="login.php?role=worker">
                        <span class="portal-card__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                        <span class="portal-card__title">Worker Portal</span>
                        <span class="portal-card__meta">Attendance & tasks</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="container landing-features" id="solutions">
            <div class="feature-header">
                <span class="eyebrow">Why teams use Texwear</span>
                <h2>Built for apparel operations, not generic admin tools.</h2>
            </div>

            <div class="feature-grid">
                <article class="feature-card">
                    <span class="feature-card__icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
                    <h3>Order visibility</h3>
                    <p>Track progress from buyer order intake through cutting, sewing, inspection, and dispatch.</p>
                </article>
                <article class="feature-card">
                    <span class="feature-card__icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
                    <h3>Production control</h3>
                    <p>Monitor workstations, stage completion, and daily operational performance with clarity.</p>
                </article>
                <article class="feature-card">
                    <span class="feature-card__icon"><i class="bi bi-people-fill" aria-hidden="true"></i></span>
                    <h3>Workforce activity</h3>
                    <p>Keep worker attendance, task ownership, and production updates aligned in real time.</p>
                </article>
            </div>
        </section>
    </main>

    <footer class="landing-footer">
        <div class="container landing-footer__inner">
            <div>
                <strong>Texwear Ltd</strong>
                <small>Garments operations platform</small>
            </div>
            <p>© <?= date('Y'); ?> Texwear Ltd. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
