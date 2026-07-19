<?php
// admin/login.php - the gate in front of the entire admin panel.
// Every other file in /admin checks $_SESSION['admin_id'] and bounces back
// here if it's not set, so this is the only way in.
require_once '../config.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // only accounts with is_admin = 1 are allowed to log in here, even if
    // the username/password match a regular customer account
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND is_admin = 1");
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['user_id'];
        $_SESSION['admin_username'] = $admin['username']; // used to show "Hi, X" once logged in
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid admin login.";
    }
}

// This page intentionally does NOT include admin/includes/header.php - that
// file renders the full admin nav bar (Dashboard, Products, Users...), which
// doesn't make sense to show before someone is actually logged in. Instead
// this is a lightweight standalone page that still pulls in whichever theme
// is currently active, so it still looks like part of the same site.
$theme = 'regular';
$login_settings_check = @mysqli_query($conn, "SELECT setting_value FROM site_settings WHERE setting_name = 'site_template' LIMIT 1");
if ($login_settings_check) {
    $login_settings_row = mysqli_fetch_assoc($login_settings_check);
    if ($login_settings_row && in_array($login_settings_row['setting_value'], ['regular', 'dark', 'retro'])) {
        $theme = $login_settings_row['setting_value'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Admin Login - GenX Coding</title>
<link rel="icon" href="../images/favicon.png" type="image/png">
<link rel="stylesheet" href="../css/<?php echo $theme; ?>.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="page-wrapper">
<header class="site-header">
    <div class="header-inner">
        <div class="logo"><a href="../index.php">GenX<span>Coding</span></a></div>
    </div>
</header>
<main class="site-main">
    <div class="container" style="max-width: 420px;">
        <h1>Admin Login</h1>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <label>Admin Username</label>
            <input type="text" name="username" required autofocus>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <p style="font-size:13px; color:var(--text-muted); margin-top: 16px;">
            <a href="../index.php">&larr; Back to the store</a>
        </p>
    </div>
</main>
<footer class="site-footer">
    <p class="copyright">GenX Coding Admin Panel</p>
</footer>
</div>
</body>
</html>
