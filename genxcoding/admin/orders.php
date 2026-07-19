<?php
// admin/orders.php - lists every order placed on the site and lets the
// admin move each one through Pending -> Shipped -> Delivered.
require_once '../config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

// ---- update an order's status ----
// Each order row below has its own little status <select> + Update button,
// so multiple orders can be updated independently without one big form.
if (isset($_POST['update_status'])) {
    $id = intval($_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE order_id = $id");
}

// join with users so we can show *who* placed each order, not just a user_id number
$sql = "SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.user_id ORDER BY o.created_at DESC";
$result = mysqli_query($conn, $sql);

$adminPageTitle = "Orders";
include 'includes/header.php';
?>

<h1>All Orders</h1>

<table class="admin-table">
<tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td>#<?php echo $row['order_id']; ?></td>
    <td><?php echo htmlspecialchars($row['username']); ?></td>
    <td>$<?php echo number_format($row['total'], 2); ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
            <select name="status">
                <option <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                <option <?php if($row['status']=='Shipped') echo 'selected'; ?>>Shipped</option>
                <option <?php if($row['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
            </select>
            <button type="submit" name="update_status" class="btn btn-small">Update</button>
        </form>
    </td>
    <td><?php echo $row['created_at']; ?></td>
</tr>
<?php } ?>
</table>

<?php include 'includes/footer.php'; ?>
