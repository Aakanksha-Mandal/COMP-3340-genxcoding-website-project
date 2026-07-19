<?php
require_once '../config.php';
$pageTitle = "Help - Updating Site Content";
$pageDescription = "Non-programmer instructions for adding products, images, and videos to GenX Coding.";
$pageKeywords = "help, update content, add products, add images, site maintenance";
$helpLink = 'wiki/help6.php';
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

<h2 id="add-remove-products">6. Adding, Editing, or Removing Products</h2>
<p>You don't need to know how to code to keep the catalogue fresh. Everything below is done through
the Admin Panel at <code>/admin/login.php</code>.</p>
<ol>
    <li>Log in to the admin panel and click <strong>Manage Products</strong>.</li>
    <li>To add a new product, fill in the name, description, price, and an image filename, then click
        <strong>Add Product</strong>.</li>
    <li>To update a product's price, find it in the table, type the new price, and click <strong>Save</strong>.</li>
    <li>To remove a product, click <strong>Delete</strong> next to it.</li>
</ol>

<h2 id="add-images">Adding Product Images</h2>
<p>Images live in the <code>/images</code> folder on the server. To add a new one:</p>
<ol>
    <li>Prepare your image (PNG or JPG both work) and give it a short, no-spaces filename, e.g. <code>new-mug.png</code>.</li>
    <li>Upload it into the <code>/images</code> folder using your hosting file manager or FTP.</li>
    <li>In the admin panel's product form, type that exact filename into the "image" field so the product
        knows which picture to use.</li>
</ol>

<h2 id="add-videos">Adding Videos</h2>
<p>Videos live in the <code>/videos</code> folder. The site already has 3 video players waiting for files -
just drop a video file into <code>/videos</code> using the exact filename it expects and it'll appear
automatically, no code changes needed:</p>
<ul>
    <li><code>desk-banner.mp4</code> - looping background video in the homepage hero banner (desk setup aesthetic)</li>
    <li><code>brand-story.mp4</code> - shows on the About page (short brand/marketing video)</li>
    <li><code>product-highlights.mp4</code> - shows on the About page (a look at a few featured products)</li>
</ul>
<p>Want a video somewhere new, or with a different filename? Just update the <code>&lt;video&gt;</code>
tag's <code>src</code> attribute on that page to match.</p>

<h2 id="change-look">Changing the Site's Look</h2>
<p>See <a href="help5.php">Help Page 5</a> for how the admin can switch between the Regular, Dark, and
Retro templates for the whole site.</p>
</div>
<?php include '../includes/footer.php'; ?>
