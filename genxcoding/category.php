<?php
// category.php - shows products belonging to a single category, e.g.
// category.php?id=2 for "Clothing". Reached from the footer's "Shop by
// Category" links, or the category pills on this page and products.php.
require_once 'config.php';
require_once 'includes/helpers.php';

$cat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// look up the category itself first - we need its name for the page title
// and to confirm the id in the URL is actually valid before doing anything else
$cat_result = mysqli_query($conn, "SELECT * FROM categories WHERE category_id = $cat_id");
$category = mysqli_fetch_assoc($cat_result);

if (!$category) {
    die("Category not found.");
}

$pageTitle = $category['name'];
$pageDescription = "Shop " . $category['name'] . " at GenX Coding - developer merch and desk gear.";
$pageKeywords = strtolower($category['name']) . ", coding merch, developer gear, GenX Coding";
$helpLink = 'wiki/help1.php#browsing';
include 'includes/header.php';

// all categories, for the quick-filter pill bar at the top of the page
$all_categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");

// the actual products belonging to this category
$products_result = mysqli_query($conn, "SELECT * FROM products WHERE category_id = $cat_id ORDER BY name");
?>

<div class="container" style="padding-bottom: 0;">
    <h1><?php echo $category['name']; ?></h1>

    <div class="category-pills">
        <a href="products.php">All Products</a>
        <?php while ($c = mysqli_fetch_assoc($all_categories)) { ?>
            <!-- highlight the pill for whichever category we're currently viewing -->
            <a href="category.php?id=<?php echo $c['category_id']; ?>" class="<?php echo ($c['category_id'] == $cat_id) ? 'active' : ''; ?>">
                <?php echo $c['name']; ?>
            </a>
        <?php } ?>
    </div>
</div>

<div class="product-grid">
    <?php if (mysqli_num_rows($products_result) == 0): ?>
        <p>No products in this category yet.</p>
    <?php endif; ?>
    <?php while ($row = mysqli_fetch_assoc($products_result)) { ?>
        <div class="product-card">
            <div class="product-img-wrap">
                <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" title="Image generated with Google Gemini">
                <?php if (is_on_sale($row)) { ?><span class="sale-badge">Sale</span><?php } ?>
            </div>
            <h3><?php echo $row['name']; ?></h3>
            <p class="price"><?php echo render_price($row); ?></p>
            <a href="product.php?id=<?php echo $row['product_id']; ?>" class="btn">View</a>
        </div>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
