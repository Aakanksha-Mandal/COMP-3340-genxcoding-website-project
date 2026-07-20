<?php
// admin/dashboard.php - the admin panel's homepage. Shows quick totals for
// products/orders/users plus a simple bar chart comparing them, so whoever
// is running the store gets a snapshot without digging through each page.
require_once '../config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

// three quick counts pulled straight from the database
$product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM products"))['c'];
$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM orders"))['c'];
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];

$adminPageTitle = "Dashboard";
include 'includes/header.php';
?>

<h1>Admin Dashboard</h1>

<div class="stat-cards">
    <div class="stat-card">
        <span class="stat-number"><?php echo $product_count; ?></span>
        <span class="stat-label">Products</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $order_count; ?></span>
        <span class="stat-label">Orders</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $user_count; ?></span>
        <span class="stat-label">Users</span>
    </div>
</div>

<!-- simple bar chart using plain divs + css - lightweight, no charting library needed -->
<h2>Orders vs Products vs Users</h2>
<div class="chart">
    <div class="bar" style="height: <?php echo $product_count * 10; ?>px;">Products</div>
    <div class="bar" style="height: <?php echo $order_count * 10; ?>px;">Orders</div>
    <div class="bar" style="height: <?php echo $user_count * 10; ?>px;">Users</div>
</div>

<?php include 'includes/footer.php'; ?>
