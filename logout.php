<?php
require_once __DIR__ . '/config/auth.php';

garments_session_start_safe();
garments_logout_user();
header('Location: index.php');
exit;
?>