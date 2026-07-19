<?php
// admin/includes/header.php
// Shared <head> + top navigation bar for every admin page. This exists so
// the admin panel visually matches the public site (same header bar, same
// fonts, same CSS classes) instead of looking like a separate, uglier app
// bolted on the side - that mismatch was flagged as a real usability/
// polish problem, so this file fixes it in one place for every admin page.
// One-row layout: nav links sit right after the logo, account links are
// pinned to the far right via margin-left:auto with a divider between them.
//
// Every admin page must set $adminPageTitle before including this file,
// and must have already checked $_SESSION['admin_id'] itself (this file
// does NOT do the login check - that stays in each page so pages can
// redirect before any HTML is sent).

// Reuse the exact same "which template is active" logic as the public
// site, so the admin panel always matches whatever look (Regular/Dark/
// Retro) is currently live - that's the "uniform" part of the request.
$theme = 'regular';
$admin_settings_check = @mysqli_query($conn, "SELECT setting_value FROM site_settings WHERE setting_name = 'site_template' LIMIT 1");
if ($admin_settings_check) {
    $admin_settings_row = mysqli_fetch_assoc($admin_settings_check);
    if ($admin_settings_row && in_array($admin_settings_row['setting_value'], ['regular', 'dark', 'retro'])) {
        $theme = $admin_settings_row['setting_value'];
    }
}

// same cache-busting trick as the public header.php, so CSS edits show up
// immediately instead of needing a hard-refresh
$admin_css_path = $_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/css/' . $theme . '.css';
$admin_css_version = file_exists($admin_css_path) ? filemtime($admin_css_path) : time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow"> <!-- admin pages shouldn't show up in search engines -->
<title><?php echo isset($adminPageTitle) ? $adminPageTitle . " - Admin - GenX Coding" : "Admin - GenX Coding"; ?></title>
<link rel="icon" href="<?php echo BASE_URL; ?>/images/favicon.png" type="image/png">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/<?php echo $theme; ?>.css?v=<?php echo $admin_css_version; ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="page-wrapper">

<header class="site-header">
    <div class="header-inner">
        <div class="logo">
            <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">GenX<span>Coding</span> <small style="opacity:.6; font-weight:400;">/ admin</small></a>
        </div>
        <button id="menuBtn" class="menu-btn" aria-label="Toggle menu">&#9776;</button>

        <nav class="main-nav" id="mainNav">
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Dashboard</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/products.php">Products</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/categories.php">Categories</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/users.php">Users</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/orders.php">Orders</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/templates.php">Template</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/monitor.php">Site Status</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/help.php">Help</a></li>
            </ul>
        </nav>

        <!-- account links pinned to the far right with margin-left:auto,
             separated from the nav by a thin vertical divider -->
        <ul class="auth-links">
            <?php if (isset($_SESSION['admin_username'])) { ?>
                <li class="welcome-text">Hi, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></li>
            <?php } ?>
            <li><a href="<?php echo BASE_URL; ?>/index.php" target="_blank" aria-label="View Live Site" title="View Live Site"><i class="fa-solid fa-arrow-up-right-from-square"></i> <span class="link-text">View Live Site</span></a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/logout.php" class="btn-nav" aria-label="Logout" title="Logout"><i class="fa-solid fa-right-from-bracket"></i> <span class="link-text">Logout</span></a></li>
        </ul>
    </div>
</header>

<main class="site-main">
<div class="container">
