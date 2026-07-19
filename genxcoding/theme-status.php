<?php
// theme-status.php
// A tiny JSON endpoint that reports which of the 3 site-wide templates
// (regular/dark/retro) is currently active. The PHP pages don't need this -
// they already read site_settings directly in includes/header.php. This
// exists specifically for the genuinely-static HTML pages in /static
// (faq.html, shipping.html, etc.), which have no PHP at all and therefore
// can't query the database themselves. js/main.js calls this on page load
// for those pages and swaps their stylesheet to match, so switching the
// site template in the admin panel doesn't leave the static pages stuck
// looking like "Regular" while the rest of the site changes.
// (Same pattern as session-status.php - see the comment there.)

require_once 'config.php';
header('Content-Type: application/json');

$theme = 'regular'; // safe fallback, same default used in includes/header.php
$settings_check = @mysqli_query($conn, "SELECT setting_value FROM site_settings WHERE setting_name = 'site_template' LIMIT 1");
if ($settings_check) {
    $settings_row = mysqli_fetch_assoc($settings_check);
    if ($settings_row && in_array($settings_row['setting_value'], ['regular', 'dark', 'retro'])) {
        $theme = $settings_row['setting_value'];
    }
}

echo json_encode(['theme' => $theme]);
