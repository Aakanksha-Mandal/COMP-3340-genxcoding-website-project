<?php
// admin/products.php - lets the admin add products, reassign existing
// products to any category, update pricing (including sale pricing), and
// delete products. Category creation/renaming/deletion itself now lives on
// its own page - see admin/categories.php - this page just consumes that
// list to populate its category dropdowns.
// Access is gated by the admin_id session check below (set by admin/login.php).
require_once '../config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

// ---- add a brand new product ----
// Triggered by the "Add Product" form further down this file.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $image = mysqli_real_escape_string($conn, $_POST['image']); // just the filename, e.g. "mug.png" - the actual image file still needs to be uploaded to /images separately
    $category_id = intval($_POST['category_id']);
    // sale_price is optional - if the admin leaves it blank we store NULL,
    // which is what render_price()/is_on_sale() in includes/helpers.php
    // check for to decide whether to show a "Sale" badge on the storefront
    $sale_price = ($_POST['sale_price'] !== '') ? floatval($_POST['sale_price']) : null;
    $sale_price_sql = ($sale_price === null) ? "NULL" : $sale_price;

    mysqli_query($conn, "INSERT INTO products (name, description, price, sale_price, image, category_id) VALUES ('$name','$desc',$price,$sale_price_sql,'$image',$category_id)");
    // redirect back to this page (Post/Redirect/Get) so refreshing after
    // adding a product does a fresh GET instead of resubmitting the form
    // and inserting the same product a second time.
    header("Location: products.php");
    exit;
}

// ---- delete a product ----
// Triggered by the "Delete" button in the product table below (with a JS
// confirm() prompt first so a stray click can't wipe out a product).
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE product_id = $id");
}

// ---- update an existing product: price, sale price, AND category ----
// Each row in the table below has its own mini edit form for these three
// fields. This is where "edit an existing product and assign it to a
// (possibly brand new) category" happens - the dropdown lists every
// category from admin/categories.php, so reassigning a product is just
// picking a different option and clicking Save.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = intval($_POST['product_id']);
    $price = floatval($_POST['price']);
    $sale_price = ($_POST['sale_price'] !== '') ? floatval($_POST['sale_price']) : null;
    $sale_price_sql = ($sale_price === null) ? "NULL" : $sale_price;
    $category_id = intval($_POST['category_id']);
    mysqli_query($conn, "UPDATE products SET price = $price, sale_price = $sale_price_sql, category_id = $category_id WHERE product_id = $id");
    // same Post/Redirect/Get reasoning as the add handler above
    header("Location: products.php");
    exit;
}

// pull products together with their category name (so the table below can
// show "Clothing" instead of a raw category_id number)
$result = mysqli_query($conn, "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.product_id
");

// full category list, re-queried fresh on every page load so a category
// added on admin/categories.php a second ago already shows up in every
// dropdown below - cached as a plain array since we need to loop over it
// once per product row, not just once for a single form
$categories_for_add_form = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
$categories_all = [];
$cat_query = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
while ($c = mysqli_fetch_assoc($cat_query)) {
    $categories_all[] = $c;
}

$adminPageTitle = "Manage Products";
include 'includes/header.php';
?>

<h1>Manage Products</h1>
<p style="color:var(--text-muted); font-size:14px;">Need a new category, or want to rename/delete one?
Head to <a href="categories.php">Manage Categories</a> - any changes made there show up in the dropdowns
below immediately.</p>

<h2>Add New Product</h2>
<form method="post">
    <label>Name</label>
    <input type="text" name="name" placeholder="Product name" required>
    <label>Description</label>
    <input type="text" name="description" placeholder="Short description">
    <label>Category</label>
    <select name="category_id" required>
        <?php while ($cat = mysqli_fetch_assoc($categories_for_add_form)) { ?>
            <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['name']; ?></option>
        <?php } ?>
    </select>
    <label>Price ($)</label>
    <input type="number" step="0.01" name="price" placeholder="29.99" required>
    <label>Sale Price ($) - optional, leave blank if not on sale</label>
    <input type="number" step="0.01" name="sale_price" placeholder="e.g. 19.99">
    <label>Image filename</label>
    <input type="text" name="image" placeholder="e.g. mug.png (upload the actual file to /images separately)">
    <button type="submit" name="add" class="btn">Add Product</button>
</form>

<h2>Existing Products</h2>
<p style="color:var(--text-muted); font-size:14px;">Change a product's category, price, or sale price
right in its row, then click Save.</p>
<table class="admin-table">
<tr><th>ID</th><th>Name</th><th>Category / Price / Sale Price</th><th>Delete</th></tr>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['product_id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
            <select name="category_id" title="Category">
                <?php foreach ($categories_all as $cat) { ?>
                    <option value="<?php echo $cat['category_id']; ?>" <?php echo ($cat['category_id'] == $row['category_id']) ? 'selected' : ''; ?>>
                        <?php echo $cat['name']; ?>
                    </option>
                <?php } ?>
            </select>
            <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" title="Regular price">
            <input type="number" step="0.01" name="sale_price" value="<?php echo $row['sale_price']; ?>" placeholder="sale price" title="Sale price (blank = not on sale)">
            <button type="submit" name="edit" class="btn btn-small">Save</button>
        </form>
    </td>
    <td><a href="?delete=<?php echo $row['product_id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this product? This cannot be undone.')">Delete</a></td>
</tr>
<?php } ?>
</table>

<?php include 'includes/footer.php'; ?>
