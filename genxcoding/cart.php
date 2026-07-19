<?php
// cart.php - shows everything currently in the visitor's session-based cart,
// lets them remove items, and totals everything up before checkout.
require_once 'config.php';

// Handle "remove item" BEFORE including the header. header("Location: ...")
// has to run before any HTML is sent to the browser, and includes/header.php
// outputs the whole <head>/<body>/nav - so this has to happen first or PHP
// throws a "headers already sent" warning and the redirect silently fails.
if (isset($_GET['remove'])) {
    $index = intval($_GET['remove']);
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // re-index the array so keys stay sequential
    header("Location: cart.php");
    exit;
}

$pageTitle = "Your Cart";
$pageDescription = "Review the items in your GenX Coding shopping cart before checkout.";
$pageKeywords = "shopping cart, checkout, coding merch, GenX Coding";
$helpLink = 'wiki/help2.php#cart';
include 'includes/header.php';

$total = 0; // running total, added up as we loop through cart items below
?>

<div class="container">
<h1>Your Shopping Cart</h1>

<?php if (empty($_SESSION['cart'])): ?>
    <p>Your cart is empty. <a href="products.php">Go shopping!</a></p>
<?php else: ?>
    <table class="cart-table">
        <tr><th>Product</th><th>Option</th><th>Qty</th><th>Price</th><th></th></tr>
        <?php foreach ($_SESSION['cart'] as $i => $item) {
            // each cart item is a plain array stored in the session (see
            // product.php / ajax-add-to-cart.php for how items get added)
            $line_total = $item['price'] * $item['qty'];
            $total += $line_total;
        ?>
        <tr>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['option']; ?></td>
            <td><?php echo $item['qty']; ?></td>
            <td>$<?php echo number_format($line_total, 2); ?></td>
            <td><a href="cart.php?remove=<?php echo $i; ?>">Remove</a></td>
        </tr>
        <?php } ?>
    </table>

    <h3>Total: $<?php echo number_format($total, 2); ?></h3>
    <a href="checkout.php" class="btn">Checkout</a>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
