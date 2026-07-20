<?php
require_once 'config.php';

// where to send the person back to after subscribing (the page they were on)
$back = isset($_POST['came_from']) && $_POST['came_from'] !== '' ? $_POST['came_from'] : 'index.php';
// safety check - only allow relative paths within this site
if (strpos($back, '://') !== false || strpos($back, '//') === 0) {
    $back = 'index.php';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    // INSERT IGNORE so subscribing twice with the same email doesn't error out
    mysqli_query($conn, "INSERT IGNORE INTO newsletter_subscribers (email) VALUES ('$email')");
}

// glue the "subscribed=1" flag onto the return URL so the footer can show a thank-you message
$separator = (strpos($back, '?') !== false) ? '&' : '?';
header("Location: " . $back . $separator . "subscribed=1#newsletter");
exit;
