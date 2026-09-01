<?php
require_once __DIR__ . '/../config/auth.php';

$scriptName = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
if (str_starts_with($scriptName, 'worker_')) {
    garments_require_worker('../login.php');
} elseif ($scriptName !== 'index.php' && $scriptName !== 'login.php') {
    garments_require_incharge('../login.php');
}
$garmentsCsrfToken = garments_csrf_token();
/**
 * Shared document header for dashboard pages.
 * Pages may override these values before including this file.
 */
$pageTitle = isset($pageTitle) && is_string($pageTitle) ? $pageTitle : 'Dashboard';
$assetBase = isset($assetBase) && is_string($assetBase) ? $assetBase : '../assets/';
$garmentsCurrentUser = garments_current_user() ?? [];
$garmentsUserName = trim((string) ($garmentsCurrentUser['name'] ?? 'User'));
$garmentsUserName = $garmentsUserName !== '' ? $garmentsUserName : 'User';
$garmentsUserRole = strtolower((string) ($garmentsCurrentUser['role'] ?? ''));
$garmentsUserRoleLabel = $garmentsUserRole === 'incharge' ? 'Factory Admin' : 'Worker';
$garmentsNameParts = preg_split('/\s+/', $garmentsUserName, -1, PREG_SPLIT_NO_EMPTY) ?: ['U'];
$garmentsUserInitials = strtoupper(substr($garmentsNameParts[0], 0, 1) . (isset($garmentsNameParts[1]) ? substr($garmentsNameParts[1], 0, 1) : ''));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="garments-csrf-token" content="<?= htmlspecialchars($garmentsCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="description" content="Garments Management System operations dashboard">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> | Garments Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>css/style.css" rel="stylesheet">
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>

    <div class="app-shell">
