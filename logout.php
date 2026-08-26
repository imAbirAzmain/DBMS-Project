<?php
/**
 * In a real application, this would destroy the user's session.
 * For this prototype, it simply redirects back to the login page.
 */
header('Location: pages/login.php');
exit;
?>