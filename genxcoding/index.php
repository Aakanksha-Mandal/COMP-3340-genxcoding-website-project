<?php
// index.php - the homepage. Shows a hero banner, 4 featured products
// (sale items bubbled to the top), and a short "how it works" video.
require_once 'config.php';
require_once 'includes/helpers.php'; // gives us render_price() and is_on_sale()

$pageTitle = "Home";
$pageDescription = "GenX Coding is an online store for developer merch - mechanical keyboards, hoodies, mugs, stickers, and desk gear for programmers.";
$pageKeywords = "coding merch, programmer gifts, developer store, mechanical keyboard, coder hoodie, GenX Coding";
$helpLink = 'wiki/help1.php';
include 'includes/header.php';

// grab 4 products for the "Featured Products" section. The ORDER BY sorts
// anything currently on sale (sale_price IS NOT NULL) to the front first,
// then falls back to highest rating - so sale items get more visibility
// without us needing a separate "is this on sale" query.
$sql = "SELECT * FROM products ORDER BY (sale_price IS NOT NULL) DESC, rating DESC LIMIT 4";
$result = mysqli_query($conn, $sql);
?>

<!-- hero: text on the left, illustration on the right (two-column layout
     defined in css/regular.css under .hero/.hero-content/.hero-image) -->
<section class="hero">
    <div class="hero-content">
        <h1>Gear made for people who code.</h1>
        <p>Keyboards, hoodies, mugs, stickers - everything a developer actually wants.</p>
        <a href="products.php" class="btn">Shop Now</a>
    </div>
    <div class="hero-image">
        <!-- looping background video showing the desk setup aesthetic -
             autoplay only works cross-browser when muted, and playsinline
             stops iOS from forcing it into fullscreen -->
        <video autoplay muted loop playsinline poster="images/hero-banner.png" aria-label="Looping video of a coding desk setup">
            <source src="videos/desk-banner.mp4" type="video/mp4">
        </video>
    </div>
</section>

<section class="featured">
    <h2>Featured Products</h2>
    <div class="product-grid">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="product-card">
                <div class="product-img-wrap">
                    <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <?php if (is_on_sale($row)) { ?><span class="sale-badge">Sale</span><?php } ?>
                </div>
                <h3><?php echo $row['name']; ?></h3>
                <p class="price"><?php echo render_price($row); ?></p>
                <a href="product.php?id=<?php echo $row['product_id']; ?>" class="btn">View Product</a>
            </div>
        <?php } ?>
    </div>
    <div style="text-align:center; padding: 0 var(--space-md) var(--space-lg);">
        <a href="products.php" class="btn" style="background:transparent; border:2px solid var(--accent); color:var(--accent);">View All Products &rarr;</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
