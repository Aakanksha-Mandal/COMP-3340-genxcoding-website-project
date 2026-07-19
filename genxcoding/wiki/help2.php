<?php
require_once '../config.php';
$pageTitle = "Help - Cart & Checkout";
$pageDescription = "How to review your cart and complete checkout on GenX Coding.";
$pageKeywords = "help, shopping cart, checkout, place order";
$helpLink = 'wiki/help2.php';
include '../includes/header.php';
?>
<div class="container">
<h1>Help Wiki</h1>
<nav class="wiki-nav">
    <a href="help1.php">1. Getting Started</a> |
    <a href="help2.php">2. Shopping Cart & Checkout</a> |
    <a href="help3.php">3. Account & Login</a> |
    <a href="help4.php">4. Leaving Reviews</a> |
    <a href="help5.php">5. Site Look & Themes</a> |
    <a href="help6.php">6. Updating Site Content</a> |
    <a href="help7.php">7. Technical Documentation</a>
</nav>

<h2 id="cart">2. Your Shopping Cart</h2>
<p>Your cart is shown at the top of the page next to a number in parentheses - that's how many items you have.</p>
<ol>
    <li>Click <strong>Cart</strong> in the menu to review what you've added.</li>
    <li>You can remove any item by clicking the <strong>Remove</strong> link next to it.</li>
    <li>When you're ready, click <strong>Checkout</strong>.</li>
</ol>

<h2 id="checkout-steps">Completing Checkout</h2>
<ol>
    <li>You must be logged in to check out - if you aren't, you'll be asked to log in first.</li>
    <li>Fill in your name, address, and pick a payment method.</li>
    <li>Click <strong>Place Order</strong> and you'll get an order number right away.</li>
    <li>You can see all your past orders any time under <strong>My Account</strong>.</li>
</ol>
</div>
<?php include '../includes/footer.php'; ?>
