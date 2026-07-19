<?php
require_once '../config.php';
$pageTitle = "Help - Account & Login";
$pageDescription = "How to register, log in, and manage your GenX Coding account.";
$pageKeywords = "help, register, login, account, profile";
$helpLink = 'wiki/help3.php';
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

<h2 id="register">3. Creating an Account</h2>
<p>Creating an account lets you check out, leave reviews, and see your order history.</p>
<ol>
    <li>Click <strong>Register</strong> in the top menu.</li>
    <li>Enter a username, email, and password. Passwords are securely hashed - not even the admin can see your real password.</li>
</ol>

<h2 id="login">Logging In</h2>
<ol>
    <li>Go to <strong>Login</strong> and enter your username and password.</li>
    <li>If your account gets disabled by an admin (usually for abuse), you'll see a message when trying to log in - contact us via the Contact page.</li>
</ol>

<h2 id="profile">Managing Your Profile</h2>
<ol>
    <li>Once logged in, click <strong>My Account</strong>.</li>
    <li>You can update your email address or set a new password right from that page - just leave the password field blank if you don't want to change it.</li>
    <li>Below that, you'll see your full order history.</li>
</ol>
</div>
<?php include '../includes/footer.php'; ?>
