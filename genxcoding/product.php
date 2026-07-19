<?php
// product.php - single product detail page. Handles 3 things: showing the
// product + its options, the "add to cart" form (DYNAMIC FORM #1), and the
// star-rating review form below it.
require_once 'config.php';
require_once 'includes/helpers.php';

// which product to show comes from the URL, e.g. product.php?id=5
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM products WHERE product_id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found.");
}

$pageTitle = $product['name'];
// build a unique, product-specific meta description instead of reusing the
// same site-wide one on every product page - this is what real SEO wants:
// each page describing its own actual content.
$pageDescription = $product['name'] . " - " . substr(strip_tags($product['description']), 0, 140);
$pageKeywords = strtolower($product['name']) . ", coding merch, developer gear, GenX Coding";
$helpLink = 'wiki/help1.php#add-to-cart';
include 'includes/header.php';

// get the options for this product (like Size, Color etc)
$opt_sql = "SELECT * FROM product_options WHERE product_id = $id";
$opt_result = mysqli_query($conn, $opt_sql);
$options = [];
while ($o = mysqli_fetch_assoc($opt_result)) {
    $options[$o['option_name']][] = $o['option_value'];
}

// handle "add to cart" form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    // this was the bug - guests could add to cart, but the header only shows
    // the cart icon to logged-in users, so items silently vanished from view.
    // Now we just require login before adding anything, and send guests to
    // the login page, which sends them right back here afterwards.
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?next=" . urlencode("product.php?id=$id"));
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][] = [
        'product_id' => $id,
        'name' => $product['name'],
        'price' => $product['price'],
        'option' => $_POST['chosen_option'] ?? 'Default',
        'qty' => intval($_POST['qty'])
    ];
    header("Location: cart.php"); // send them to the cart after adding
    exit;
}

// handle review submit (only logged in users)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    if (isset($_SESSION['user_id'])) {
        $rating = intval($_POST['rating']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $uid = $_SESSION['user_id'];
        mysqli_query($conn, "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES ($id, $uid, $rating, '$comment')");
    }
    // redirect back to this same product page after handling the POST, so a
    // page refresh does a fresh GET instead of resubmitting the review form
    // and inserting a duplicate row every time (Post/Redirect/Get pattern -
    // same reason the add-to-cart handler above already redirects).
    header("Location: product.php?id=$id");
    exit;
}
?>

<div class="product-detail">
    <div class="product-img-wrap" style="max-width:360px;">
        <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <?php if (is_on_sale($product)) { ?><span class="sale-badge">Sale</span><?php } ?>
    </div>
    <div class="product-info">
        <h1><?php echo $product['name']; ?></h1>
        <p><?php echo $product['description']; ?></p>
        <p class="price"><?php echo render_price($product); ?></p>
        <p>Rating: <?php echo $product['rating']; ?> / 5</p>

        <!-- DYNAMIC FORM #1 (one of the two required dynamic forms).
             Has an id so main.js can optionally submit it via AJAX for a
             logged-in user, with a normal fallback if JS is off. -->
        <form method="post" id="add-to-cart-form" data-logged-in="<?php echo isset($_SESSION['user_id']) ? '1' : '0'; ?>">
            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
            <?php foreach ($options as $optName => $values) { ?>
                <label><?php echo $optName; ?>:</label>
                <select name="chosen_option">
                    <?php foreach ($values as $v) { ?>
                        <option value="<?php echo $optName . ": " . $v; ?>"><?php echo $v; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>

            <label>Quantity:</label>
            <input type="number" name="qty" value="1" min="1" max="10">

            <button type="submit" name="add_to_cart" id="add-to-cart-btn" class="btn">Add to Cart</button>
        </form>
    </div>
</div>

<!-- reviews section -->
<section class="reviews">
    <h2>Reviews</h2>
    <?php
    $rev_sql = "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE product_id = $id";
    $rev_result = mysqli_query($conn, $rev_sql);
    while ($rev = mysqli_fetch_assoc($rev_result)) {
    ?>
        <div class="review">
            <strong><?php echo $rev['username']; ?></strong> - <?php echo $rev['rating']; ?>/5
            <p><?php echo htmlspecialchars($rev['comment']); ?></p>
        </div>
    <?php } ?>

    <?php if (isset($_SESSION['user_id'])): ?>
    <form method="post">
        <label>Your Rating:</label>
        <select name="rating">
            <option value="5">5</option>
            <option value="4">4</option>
            <option value="3">3</option>
            <option value="2">2</option>
            <option value="1">1</option>
        </select>
        <textarea name="comment" placeholder="Write your review..."></textarea>
        <button type="submit" name="submit_review" class="btn">Submit Review</button>
    </form>
    <?php else: ?>
        <p><a href="login.php">Log in</a> to leave a review.</p>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
