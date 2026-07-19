<?php
// admin/users.php - lists every registered account and lets the admin
// disable/re-enable logins without deleting the account or its order history.
require_once '../config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

// ---- toggle a user's active status ----
// "1 - is_active" flips 1 to 0 and 0 to 1 in a single query, so clicking the
// same link again just toggles it back - this is how admin "disables"/
// "re-enables" an account. login.php checks is_active on every login attempt
// and blocks disabled accounts with a message.
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    mysqli_query($conn, "UPDATE users SET is_active = 1 - is_active WHERE user_id = $id");
}

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY user_id");

$adminPageTitle = "Manage Users";
include 'includes/header.php';
?>

<h1>Manage Users</h1>
<p style="color:var(--text-muted); font-size:14px;">Disabling an account blocks future logins - it does not delete the account or its order history.</p>

<table class="admin-table">
<tr><th>ID</th><th>Username</th><th>Email</th><th>Admin?</th><th>Status</th><th>Action</th></tr>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['user_id']; ?></td>
    <td><?php echo htmlspecialchars($row['username']); ?></td>
    <td><?php echo htmlspecialchars($row['email']); ?></td>
    <td><?php echo $row['is_admin'] ? 'Yes' : 'No'; ?></td>
    <td>
        <?php if ($row['is_active']) { ?>
            <span style="color:#2e7d32; font-weight:600;">Active</span>
        <?php } else { ?>
            <span style="color:#c0392b; font-weight:600;">Disabled</span>
        <?php } ?>
    </td>
    <td>
        <a href="?toggle=<?php echo $row['user_id']; ?>" class="btn btn-small <?php echo $row['is_active'] ? 'btn-danger' : ''; ?>">
            <?php echo $row['is_active'] ? 'Disable' : 'Enable'; ?>
        </a>
    </td>
</tr>
<?php } ?>
</table>

<?php include 'includes/footer.php'; ?>
