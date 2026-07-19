<?php
// admin/monitor.php - a quick health check for the site's core features.
// This satisfies the "monitoring page reporting online/offline status"
// requirement - it's intentionally simple (no external monitoring service),
// just a handful of checks run fresh on every page load.
require_once '../config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$checks = [];

// 1. database connection check - if $conn is falsy, config.php would have
//    already died with an error before we even got here, but we check
//    anyway for a clear green/red row
$checks['Database Connection'] = $conn ? 'Online' : 'Offline';

// 2. products table has data (an empty catalogue would mean something's wrong)
$p = mysqli_query($conn, "SELECT COUNT(*) as c FROM products");
$checks['Products Table'] = (mysqli_fetch_assoc($p)['c'] > 0) ? 'Online' : 'Offline';

// 3. users table has data (at minimum the admin account should exist)
$u = mysqli_query($conn, "SELECT COUNT(*) as c FROM users");
$checks['Users Table'] = (mysqli_fetch_assoc($u)['c'] > 0) ? 'Online' : 'Offline';

// 4. site_settings table - if this is missing, the theme system falls back
//    to "regular" silently (see includes/header.php), so it's worth flagging
$s = @mysqli_query($conn, "SELECT COUNT(*) as c FROM site_settings");
$checks['Site Settings Table'] = ($s && mysqli_fetch_assoc($s)['c'] > 0) ? 'Online' : 'Offline';

// 5. images folder exists and actually has files in it (not just an empty folder)
$image_files = is_dir('../images') ? glob('../images/*.{png,jpg,jpeg,svg}', GLOB_BRACE) : [];
$checks['Images Folder'] = (count($image_files) > 0) ? 'Online' : 'Offline';

// 6. videos folder - same idea, flags clearly if no videos have been added yet
$video_files = is_dir('../videos') ? glob('../videos/*.mp4') : [];
$checks['Videos Folder'] = (count($video_files) > 0) ? 'Online' : 'Offline';

// 7. session/login system - session_start() already ran inside config.php,
//    so this just confirms PHP sessions are actually active on this server
$checks['Session System'] = session_status() == PHP_SESSION_ACTIVE ? 'Online' : 'Offline';

$adminPageTitle = "Site Status";
include 'includes/header.php';
?>

<h1>Site Status Monitor</h1>
<p style="color:var(--text-muted); font-size:14px;">Last checked: <?php echo date('Y-m-d H:i:s'); ?> (refresh this page to re-check)</p>

<table class="admin-table">
<tr><th>Feature</th><th>Status</th></tr>
<?php foreach ($checks as $feature => $status) { ?>
<tr>
    <td><?php echo $feature; ?></td>
    <td>
        <?php if ($status == 'Online') { ?>
            <span style="color:#2e7d32; font-weight:700;">&#9679; Online</span>
        <?php } else { ?>
            <span style="color:#c0392b; font-weight:700;">&#9679; Offline</span>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</table>

<?php include 'includes/footer.php'; ?>
