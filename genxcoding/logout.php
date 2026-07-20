<?php
require_once 'config.php';
// wipe the session and go home
session_destroy();
header("Location: index.php");
exit;
?>
