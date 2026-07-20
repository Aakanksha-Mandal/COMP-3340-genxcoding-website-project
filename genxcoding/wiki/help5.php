<?php
require_once '../config.php';
$pageTitle = "Help - Site Look & Themes";
$pageDescription = "How GenX Coding's site-wide themes and automatic dark mode work.";
$pageKeywords = "help, themes, dark mode, site templates";
$helpLink = 'wiki/help5.php';
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

<h2 id="themes">5. Site Look &amp; Themes</h2>
<p>GenX Coding has 3 different site-wide looks: <strong>Regular</strong> (default), <strong>Dark</strong>,
and <strong>Retro</strong>. Unlike some sites, you can't pick your own theme here - the site
administrator sets one consistent look for everyone, from the admin panel.</p>

<p>That said, if you're using the <strong>Regular</strong> template (the default) and your phone,
laptop, or browser is set to dark mode, GenX Coding will automatically switch to a dark color
scheme for you - no toggle needed. This is done using a modern CSS feature that detects your
system's dark/light setting automatically. If the admin has switched the whole site to the
Dark or Retro template instead, that fixed look is shown to everyone regardless of your device
settings, since that's a deliberate site-wide choice.</p>
</div>
<?php include '../includes/footer.php'; ?>
