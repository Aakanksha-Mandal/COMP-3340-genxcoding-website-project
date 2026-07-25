<?php
// about.php - the company/store description page, plus two brand videos.
// Mostly static content, so there isn't much PHP logic here beyond setting
// up the shared header/footer.
require_once 'config.php';
$pageTitle = "About";
$pageDescription = "Learn about GenX Coding, an online store for developer merchandise and desk gear.";
$pageKeywords = "about GenX Coding, developer merch store";
$helpLink = 'wiki/help1.php';
include 'includes/header.php';
?>

<div class="container">
<h1>About GenX Coding</h1>
<p>
GenX Coding is an online store that sells merchandise designed for programmers and computer
science enthusiasts. Most developers already love hoodies, mugs, and desk gear covered in coding
jokes, so we put it all in one place. Our catalogue includes mechanical keyboards, clothing,
drinkware, desk accessories, bags, and stickers, and every item comes with multiple options such
as size, color, or material so shoppers can pick exactly what they want. Whether you're outfitting
your home office or looking for a gift for the developer in your life, we've got something for you.
</p>
</div>

<div class="container" style="padding-top: 0;">
<h2>Our Story, In Motion</h2>
<div class="video-grid">
    <div class="video-card">
        <h3>Gear Made For People Who Code</h3>
        <p>A short look at what we're about - the desk setups, the late-night debugging sessions, and the gear that makes it a little more fun.</p>
        <div class="media-credit-wrap">
            <video autoplay muted loop playsinline width="100%" aria-label="Short brand video showing our desk setups and coding sessions" title="Video created with Meta AI Vibes">
                <source src="videos/brand-story.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <span class="media-credit-badge">Video: Meta AI Vibes</span>
        </div>
    </div>
    <div class="video-card">
        <h3>Product Highlights</h3>
        <p>A closer look at some of our most-loved pieces - from the Tab Key keyboard to the console.log(hi) tee.</p>
        <div class="media-credit-wrap">
            <video autoplay muted loop playsinline width="100%" aria-label="Short video highlighting a few featured products" title="Video created with Meta AI Vibes">
                <source src="videos/product-highlights.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <span class="media-credit-badge">Video: Meta AI Vibes</span>
        </div>
    </div>
</div>
</div>

<?php include 'includes/footer.php'; ?>
