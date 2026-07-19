<?php
// admin/logout.php - clears the admin session and sends them back to login.
require_once '../config.php';
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
header("Location: login.php");
exit;
?>
