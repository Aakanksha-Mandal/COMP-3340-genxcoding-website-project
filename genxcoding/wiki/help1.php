<?php
require_once '../config.php';
$pageTitle = "Help - Getting Started";
$pageDescription = "Step-by-step guide to browsing and shopping on GenX Coding.";
$pageKeywords = "help, how to use GenX Coding, getting started";
$helpLink = 'wiki/help1.php';
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

<h2 id="browsing">1. Getting Started &amp; Browsing</h2>
<p>Welcome to GenX Coding! Here's how to browse the site:</p>
<ol>
    <li>Click <strong>Products</strong> in the top menu to see everything we sell, or use one of the
        category pills (Keyboards &amp; Mice, Clothing, Drinkware, etc.) to narrow things down.</li>
    <li>Use the search box on the Products page to find something specific, like "mug" or "hoodie".</li>
    <li>Click <strong>View</strong> on any product to see full details, pick an option (like size or color), and choose a quantity.</li>
</ol>

<h2 id="add-to-cart">Adding an Item to Your Cart</h2>
<ol>
    <li>On a product page, choose your options and quantity, then click <strong>Add to Cart</strong>.</li>
    <li>You'll need to be logged in to add items - if you're not, you'll be sent to the login page first
        and brought right back here afterwards.</li>
    <li>Once added, the cart number in the top-right of the page updates instantly.</li>
</ol>
<p>Continue to the next help page to learn about checkout.</p>
</div>
<?php include '../includes/footer.php'; ?>
