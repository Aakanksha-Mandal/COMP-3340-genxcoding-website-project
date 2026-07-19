<?php
// checkout.php - turns the session cart into a real order (rows in the
// orders and order_items tables) once the shipping form is submitted.
require_once 'config.php';
$pageTitle = "Checkout";
$pageDescription = "Complete your GenX Coding order - enter shipping details and place your order.";
$pageKeywords = "checkout, place order, shipping, coding merch, GenX Coding";
$helpLink = 'wiki/help2.php#checkout-steps';
include 'includes/header.php';

// must be logged in to checkout
if (!isset($_SESSION['user_id'])) {
    echo "<div class='container'><p>Please <a href='login.php'>log in</a> before checking out.</p></div>";
    include 'includes/footer.php';
    exit;
}

if (empty($_SESSION['cart'])) {
    echo "<div class='container'><p>Your cart is empty.</p></div>";
    include 'includes/footer.php';
    exit;
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['qty'];
}

// DYNAMIC FORM #2 - checkout / shipping form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_SESSION['user_id'];

    // create the order
    mysqli_query($conn, "INSERT INTO orders (user_id, total, status) VALUES ($uid, $total, 'Pending')");
    $order_id = mysqli_insert_id($conn);

    // add each cart item as an order item
    foreach ($_SESSION['cart'] as $item) {
        $pid = intval($item['product_id']);
        $opt = mysqli_real_escape_string($conn, $item['option']);
        $qty = intval($item['qty']);
        $price = floatval($item['price']);
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, option_text, quantity, price) VALUES ($order_id, $pid, '$opt', $qty, $price)");
    }

    // empty the cart
    $_SESSION['cart'] = [];

    echo "<div class='container'><p>Thank you! Your order #$order_id has been placed.</p></div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="container">
<h1>Checkout</h1>
<p>Order total: $<?php echo number_format($total, 2); ?></p>

<form method="post">
    <label>Full Name:</label>
    <input type="text" name="fullname" required>

    <label>Shipping Address:</label>
    <textarea name="address" required></textarea>

    <label>Payment Method:</label>
    <select name="payment">
        <option>Credit Card</option>
        <option>Debit Card</option>
        <option>PayPal</option>
    </select>

    <button type="submit" class="btn">Place Order</button>
</form>
</div>

<?php include 'includes/footer.php'; ?>
