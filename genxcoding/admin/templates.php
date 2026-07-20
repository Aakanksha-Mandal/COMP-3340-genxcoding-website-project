<?php
// admin/templates.php - the ONLY place the site-wide template can be
// changed. Regular visitors can't switch it themselves (that's intentional -
// see wiki/help5.php for why), so this single admin control decides the
// look for every visitor at once.
require_once '../config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$message = "";

// ---- save the chosen template ----
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['template'])) {
    $chosen = $_POST['template'];
    $allowed = ['regular', 'dark', 'retro']; // whitelist - never trust the POST value directly
    if (in_array($chosen, $allowed)) {
        // site_settings is a simple key/value table - update the row if it
        // already exists, otherwise insert it (covers a fresh database that
        // hasn't had this setting touched yet)
        $check = mysqli_query($conn, "SELECT * FROM site_settings WHERE setting_name = 'site_template'");
        if (mysqli_num_rows($check) > 0) {
            mysqli_query($conn, "UPDATE site_settings SET setting_value = '$chosen' WHERE setting_name = 'site_template'");
        } else {
            mysqli_query($conn, "INSERT INTO site_settings (setting_name, setting_value) VALUES ('site_template', '$chosen')");
        }
        $message = "Site template updated to: " . ucfirst($chosen);
    }
}

// ---- find the currently active template, to pre-select the right radio button ----
$current = 'regular';
$result = mysqli_query($conn, "SELECT setting_value FROM site_settings WHERE setting_name = 'site_template'");
if ($result && mysqli_num_rows($result) > 0) {
    $current = mysqli_fetch_assoc($result)['setting_value'];
}

$adminPageTitle = "Site Template";
include 'includes/header.php';
?>

<h1>Site-Wide Template</h1>
<p>This controls the look of the <strong>entire site for every visitor</strong>. Regular users can't pick
their own theme - this keeps the store looking consistent and intentional at any given time.</p>

<?php if ($message) echo "<p style='color:#2e7d32;font-weight:600;'>$message</p>"; ?>

<form method="post">
    <div class="template-options">
        <label class="template-option <?php echo $current=='regular' ? 'active' : ''; ?>">
            <input type="radio" name="template" value="regular" <?php if ($current=='regular') echo 'checked'; ?>>
            <span class="template-swatch template-swatch-regular"></span>
            <strong>Regular</strong>
            <p>Clean navy/amber default look. Automatically switches to a dark variant for visitors whose device is set to dark mode.</p>
        </label>
        <label class="template-option <?php echo $current=='dark' ? 'active' : ''; ?>">
            <input type="radio" name="template" value="dark" <?php if ($current=='dark') echo 'checked'; ?>>
            <span class="template-swatch template-swatch-dark"></span>
            <strong>Dark</strong>
            <p>Fixed dark theme with burnt-orange accents, always dark regardless of visitor settings.</p>
        </label>
        <label class="template-option <?php echo $current=='retro' ? 'active' : ''; ?>">
            <input type="radio" name="template" value="retro" <?php if ($current=='retro') echo 'checked'; ?>>
            <span class="template-swatch template-swatch-retro"></span>
            <strong>Retro</strong>
            <p>Old terminal/CRT look, amber-on-brown, monospace, sharp-edged boxes.</p>
        </label>
    </div>
    <button type="submit" class="btn" style="margin-top: var(--space-md);">Save Template</button>
</form>

<?php include 'includes/footer.php'; ?>
