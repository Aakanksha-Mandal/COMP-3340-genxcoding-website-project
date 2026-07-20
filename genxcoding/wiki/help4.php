<?php
require_once '../config.php';
$pageTitle = "Help - Reviews";
$pageDescription = "How to rate and review products on GenX Coding.";
$pageKeywords = "help, product reviews, ratings";
$helpLink = 'wiki/help4.php';
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

<h2 id="reviews">4. Leaving Reviews</h2>
<p>You can rate any product you've viewed, even if you haven't bought it yet (a real store would probably restrict this, but we kept it open for the demo).</p>
<ol>
    <li>Go to any product page and scroll down to the <strong>Reviews</strong> section.</li>
    <li>You must be logged in to leave a review - if you're not, you'll see a link to log in instead of the review form.</li>
    <li>Pick a star rating from 1 to 5 and write a short comment.</li>
    <li>Click <strong>Submit Review</strong> and it will appear immediately below.</li>
</ol>
</div>
<?php include '../includes/footer.php'; ?>
