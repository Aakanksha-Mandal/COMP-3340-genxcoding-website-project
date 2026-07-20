<?php
// header.php - included at the top of every page.
//
// NOTE ON TEMPLATES: the site-wide look (Regular / Dark / Retro) is picked
// by the ADMIN in admin/templates.php, not by each visitor - this keeps the
// storefront's appearance consistent and intentional at any given moment
// rather than different for every shopper. We read the current choice out
// of the site_settings table below. If that table/row doesn't exist yet
// (e.g. you're running an older copy of database.sql), we just fall back
// to "regular" so the site doesn't break.

$theme = 'regular'; // safe fallback
$settings_check = @mysqli_query($conn, "SELECT setting_value FROM site_settings WHERE setting_name = 'site_template' LIMIT 1");
if ($settings_check) {
    $settings_row = mysqli_fetch_assoc($settings_check);
    if ($settings_row && in_array($settings_row['setting_value'], ['regular', 'dark', 'retro'])) {
        $theme = $settings_row['setting_value'];
    }
}

// SEO: each page can set $pageDescription / $pageKeywords before including
// this file to get its own unique meta tags (better for SEO than every page
// sharing the same description). Pages that don't set these just fall back
// to the generic site-wide description below.
if (!isset($pageDescription)) {
    $pageDescription = "GenX Coding - online store for developer merch: keyboards, hoodies, mugs, stickers and more.";
}
if (!isset($pageKeywords)) {
    $pageKeywords = "coding merch, programmer gifts, developer store, mechanical keyboard, coder hoodie";
}

// Each page can set $helpLink before including this file to point the
// "Help" nav link at the specific wiki page/section that's actually
// relevant to what the visitor is doing (e.g. checkout.php links straight
// to the checkout instructions instead of the wiki's front page).
if (!isset($helpLink)) {
    $helpLink = 'wiki/help1.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- SEO meta tags - description/keywords are page-specific, see note above -->
<meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
<meta name="author" content="GenX Coding">
<link rel="icon" href="<?php echo BASE_URL; ?>/images/favicon.png" type="image/png">
<title><?php echo isset($pageTitle) ? $pageTitle . " - GenX Coding" : "GenX Coding"; ?></title>

<!-- this file loads whichever template the admin currently has active -->
<?php
// Cache-busting: append the file's last-modified time as a version number
// so browsers always fetch the latest CSS after you update it, instead of
// serving a stale cached copy (this was causing "I updated it but it still
// looks old" bugs).
$css_path = $_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/css/' . $theme . '.css';
$css_version = file_exists($css_path) ? filemtime($css_path) : time();
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/<?php echo $theme; ?>.css?v=<?php echo $css_version; ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
</head>
<body>
<!-- page-wrapper pins the footer to the bottom even on short pages like About,
     instead of letting it float up near the header when there's not much content -->
<div class="page-wrapper">

<header class="site-header">
    <div class="header-inner">
        <div class="logo">
            <a href="<?php echo BASE_URL; ?>/index.php">GenX<span>Coding</span></a>
        </div>

        <!-- hamburger button for mobile, toggled with js/main.js -->
        <button id="menuBtn" class="menu-btn" aria-label="Toggle menu">&#9776;</button>

        <!-- main menu - sits right next to the logo -->
        <nav class="main-nav" id="mainNav">
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>/index.php">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>/products.php">Products</a></li>
                <li><a href="<?php echo BASE_URL; ?>/about.php">About</a></li>
                <li><a href="<?php echo BASE_URL; ?>/contact.php">Contact</a></li>
                <li><a href="<?php echo BASE_URL; ?>/<?php echo $helpLink; ?>">Help</a></li>
            </ul>
        </nav>

        <!-- account-related links - margin-left:auto (in the CSS) pushes this
             group all the way to the far right of the same row, with a thin
             vertical divider marking the split between the two groups -->
        <ul class="auth-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="welcome-text">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></li>
                <li><a href="<?php echo BASE_URL; ?>/cart.php" aria-label="Cart" title="Cart"><i class="fa-solid fa-cart-shopping"></i> <span class="link-text">Cart</span> <span class="cart-count-badge">(<span id="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>)</span></a></li>
                <li><a href="<?php echo BASE_URL; ?>/account.php" aria-label="My Account" title="My Account"><i class="fa-solid fa-user"></i> <span class="link-text">My Account</span></a></li>
                <li><a href="<?php echo BASE_URL; ?>/logout.php" aria-label="Logout" title="Logout"><i class="fa-solid fa-right-from-bracket"></i> <span class="link-text">Logout</span></a></li>
            <?php else: ?>
                <li><a href="<?php echo BASE_URL; ?>/login.php">Login</a></li>
                <li><a href="<?php echo BASE_URL; ?>/register.php" class="btn-nav">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>

<main class="site-main">
