<?php
require_once 'config.php';

// must be logged in to see this page at all
if (!isset($_SESSION['user_id'])) {
    $pageTitle = "My Account";
    include 'includes/header.php';
    echo "<div class='container'><p>You must <a href='login.php'>log in</a> to view this page.</p></div>";
    include 'includes/footer.php';
    exit;
}

$uid = $_SESSION['user_id'];
$profile_message = "";
$profile_error = "";

// handle profile update form (change email and/or password) - processed
// before the header include so we could redirect cleanly if needed, same
// pattern used on product.php and checkout.php.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $new_password = $_POST['new_password'];

    if ($new_email === '') {
        $profile_error = "Email cannot be blank.";
    } else {
        mysqli_query($conn, "UPDATE users SET email = '$new_email' WHERE user_id = $uid");

        // only touch the password if they actually typed a new one
        if (!empty($new_password)) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password = '$hashed' WHERE user_id = $uid");
        }
        $profile_message = "Profile updated.";
    }
}

$pageTitle = "My Account";
$pageDescription = "View your GenX Coding order history and manage your account details.";
$pageKeywords = "my account, order history, profile, GenX Coding";
$helpLink = 'wiki/help3.php#profile';
include 'includes/header.php';

// pull current profile info to pre-fill the edit form
$user_result = mysqli_query($conn, "SELECT username, email FROM users WHERE user_id = $uid");
$user_row = mysqli_fetch_assoc($user_result);

// get order history for this user
$sql = "SELECT * FROM orders WHERE user_id = $uid ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container">
<h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>

<h2>Profile</h2>
<?php if ($profile_message) { echo "<p style='color:#2e7d32;font-weight:600;'>$profile_message</p>"; } ?>
<?php if ($profile_error) { echo "<p class='error'>$profile_error</p>"; } ?>
<form method="post">
    <label>Username</label>
    <input type="text" value="<?php echo htmlspecialchars($user_row['username']); ?>" disabled>
    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user_row['email']); ?>" required>
    <label>New Password (leave blank to keep your current password)</label>
    <input type="password" name="new_password" placeholder="••••••••">
    <button type="submit" name="update_profile" class="btn">Save Changes</button>
</form>

<h2>Order History</h2>
<?php if (mysqli_num_rows($result) == 0): ?>
    <p>You have no orders yet.</p>
<?php else: ?>
    <table class="cart-table">
        <tr><th>Order #</th><th>Total</th><th>Status</th><th>Date</th></tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['order_id']; ?></td>
            <td>$<?php echo $row['total']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
        </tr>
        <?php } ?>
    </table>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
