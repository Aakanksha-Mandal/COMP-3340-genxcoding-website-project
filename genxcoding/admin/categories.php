<?php
// admin/categories.php - full CRUD for categories: add, rename, and delete,
// plus a count of how many products currently sit in each one. This is the
// category equivalent of admin/products.php - same layout, same patterns.
require_once '../config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$delete_error = "";

// ---- add a new category ----
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    if ($name !== '') {
        mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
    }
    // redirect back to this page (Post/Redirect/Get) so refreshing after
    // adding a category doesn't resubmit the form and insert a duplicate.
    header("Location: categories.php");
    exit;
}

// ---- rename an existing category ----
// Each row below has its own little rename form, same pattern as the price
// edit form on admin/products.php.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = intval($_POST['category_id']);
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    if ($name !== '') {
        mysqli_query($conn, "UPDATE categories SET name = '$name' WHERE category_id = $id");
    }
    // same Post/Redirect/Get reasoning as the add handler above
    header("Location: categories.php");
    exit;
}

// ---- delete a category ----
// Categories are linked to products via a foreign key with no automatic
// cascade or "set null" behavior, so MySQL will refuse to delete a category
// that still has products in it. Rather than let that show up as a raw
// database error, we check the product count ourselves first and show a
// friendly message telling the admin to reassign those products before
// deleting the category (reassignment happens on admin/products.php).
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $count_check = mysqli_query($conn, "SELECT COUNT(*) as c FROM products WHERE category_id = $id");
    $product_count = mysqli_fetch_assoc($count_check)['c'];

    if ($product_count > 0) {
        $delete_error = "Can't delete this category - it still has $product_count product(s) in it. Reassign them to a different category first in Manage Products.";
    } else {
        mysqli_query($conn, "DELETE FROM categories WHERE category_id = $id");
    }
}

// pull every category along with a live count of how many products are in it
$result = mysqli_query($conn, "
    SELECT c.*, COUNT(p.product_id) AS product_count
    FROM categories c
    LEFT JOIN products p ON p.category_id = c.category_id
    GROUP BY c.category_id
    ORDER BY c.name
");

$adminPageTitle = "Manage Categories";
include 'includes/header.php';
?>

<h1>Manage Categories</h1>

<?php if ($delete_error) { ?>
    <p class="error"><?php echo htmlspecialchars($delete_error); ?></p>
<?php } ?>

<h2>Add New Category</h2>
<form method="post" style="max-width: 420px;">
    <label>Category Name</label>
    <input type="text" name="name" placeholder="e.g. Outdoor Gear" required>
    <button type="submit" name="add" class="btn">Add Category</button>
</form>

<h2>Existing Categories</h2>
<p style="color:var(--text-muted); font-size:14px;">Rename a category right in its row and click Save.
A category can only be deleted once it has zero products in it - reassign products to a different
category on the <a href="products.php">Manage Products</a> page first.</p>

<table class="admin-table">
<tr><th>Name</th><th>Products in this category</th><th>Rename</th><th>Delete</th></tr>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo $row['product_count']; ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" title="Category name">
            <button type="submit" name="edit" class="btn btn-small">Save</button>
        </form>
    </td>
    <td>
        <?php if ($row['product_count'] == 0) { ?>
            <a href="?delete=<?php echo $row['category_id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this category? This cannot be undone.')">Delete</a>
        <?php } else { ?>
            <span style="color:var(--text-muted); font-size:13px;" title="Reassign its products first">Delete</span>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</table>

<?php include 'includes/footer.php'; ?>
