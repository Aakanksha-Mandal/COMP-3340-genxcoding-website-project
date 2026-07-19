<?php
// products.php - the full catalogue, with a search box and category filter
// pills. This page (plus category.php, which reuses the same product-card
// markup) is where most shoppers browse from.
require_once 'config.php';
require_once 'includes/helpers.php'; // render_price() / is_on_sale() for the sale badge + strikethrough pricing
$pageTitle = "Products";
$pageDescription = "Browse the full GenX Coding catalogue - keyboards, clothing, drinkware, desk accessories, bags, and stickers for developers.";
$pageKeywords = "coding merch catalogue, developer products, programmer gear, buy mechanical keyboard";
$helpLink = 'wiki/help1.php#browsing';
include 'includes/header.php';

// search box (GET request, so search results are a shareable/bookmarkable
// URL like products.php?search=mug). mysqli_real_escape_string() protects
// against SQL injection even though this is a simple LIKE query.
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if ($search != '') {
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY name";
} else {
    $sql = "SELECT * FROM products ORDER BY name";
}
$result = mysqli_query($conn, $sql);

// categories for the quick-filter pill bar below - clicking one sends the
// visitor to category.php?id=X instead of filtering this page directly
$all_categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
?>

<div class="container" style="padding-bottom: 0;">
<h1>All Products</h1>

<form method="get" class="search-form">
    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="btn">Search</button>
</form>

<div class="category-pills">
    <a href="products.php" class="active">All Products</a>
    <?php while ($c = mysqli_fetch_assoc($all_categories)) { ?>
        <a href="category.php?id=<?php echo $c['category_id']; ?>"><?php echo $c['name']; ?></a>
    <?php } ?>
</div>
</div>

<div class="product-grid">
    <?php if (mysqli_num_rows($result) === 0) { ?>
        <p style="grid-column: 1 / -1; color: var(--text-muted);">
            No products matched "<?php echo htmlspecialchars($search); ?>". Try a different search term,
            or <a href="products.php">browse the full catalogue</a>.
        </p>
    <?php } ?>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="product-card">
            <div class="product-img-wrap">
                <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <?php if (is_on_sale($row)) { ?><span class="sale-badge">Sale</span><?php } ?>
            </div>
            <h3><?php echo $row['name']; ?></h3>
            <p><?php echo substr($row['description'], 0, 60); ?>...</p>
            <p class="price"><?php echo render_price($row); ?></p>
            <p>Rating: <?php echo $row['rating']; ?> / 5</p>
            <a href="product.php?id=<?php echo $row['product_id']; ?>" class="btn">View</a>
        </div>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
