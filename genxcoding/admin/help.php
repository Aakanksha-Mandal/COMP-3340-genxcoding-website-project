<?php
// admin/help.php - documentation for whoever is running the admin panel
// day-to-day (not developer/setup docs - see README.md for that).
require_once '../config.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$adminPageTitle = "Admin Help";
include 'includes/header.php';
?>

<h1>Admin Help &amp; Documentation</h1>
<p>This page explains what each part of the admin panel does. It's meant for whoever is running the
store day-to-day, not for developers - see <code>README.md</code> in the project folder for technical
setup and installation instructions instead.</p>

<h2>Dashboard</h2>
<p>The homepage of the admin panel. Shows quick totals for products, orders, and users, plus a simple
bar chart comparing the three. Use this to get a quick sense of how the store is doing at a glance.</p>

<h2>Manage Categories</h2>
<p>Full control over the category list, on its own page.</p>
<ul>
    <li><strong>Add a category:</strong> type a name and click Add Category. It's immediately available
        in every category dropdown on the Manage Products page.</li>
    <li><strong>Rename a category:</strong> edit the name right in its row and click Save.</li>
    <li><strong>Delete a category:</strong> only possible once it has zero products in it - the page shows
        exactly how many products are currently in each category. If a category still has products,
        reassign them on the Manage Products page first, then come back and delete it.</li>
</ul>

<h2>Manage Products</h2>
<p>Add new products, reassign existing products between categories, set pricing (including sale pricing),
or delete products you no longer want to sell.</p>
<ul>
    <li><strong>Add a product:</strong> fill in the name, description, category, price, and image filename
        (the image itself needs to be uploaded to the <code>/images</code> folder separately - see the
        <a href="../wiki/help6.php" target="_blank">Updating Site Content</a> help page). A sale price is
        optional - leave it blank if the item isn't on sale.</li>
    <li><strong>Move an existing product to a different category:</strong> in that product's row, pick a
        different category from the dropdown and click Save. Need a category that doesn't exist yet? Create
        it on the Manage Categories page first - it'll show up here right away.</li>
    <li><strong>Run a sale on an existing product:</strong> in the product's row, type a sale price into the
        second price box (lower than the regular price) and click Save. The storefront automatically shows
        a "Sale" badge and a struck-through original price the moment you save.</li>
    <li><strong>End a sale:</strong> clear that same sale price box (leave it blank) and click Save - the
        badge disappears and the item goes back to full price.</li>
    <li><strong>Delete a product:</strong> click Delete next to it. This can't be undone, so double check first.</li>
</ul>
<p>Note: product <em>options</em> (like size or color) still need to be added directly in the database via
phpMyAdmin, since that's a less frequent task than editing prices. See the
<a href="../wiki/help7.php" target="_blank">Technical Documentation</a> page for the full database
table layout if you need to look something up.</p>

<h2>Manage Users</h2>
<p>Shows every registered customer account. Click <strong>Disable</strong> next to a user to block them
from logging in (useful if someone is abusing the site) - click <strong>Enable</strong> to restore their
access. This does not delete their account or order history, it just blocks login.</p>

<h2>View Orders</h2>
<p>Shows every order placed on the site, who placed it, and its total. Use the dropdown next to each
order to update its status as it moves through <strong>Pending &rarr; Shipped &rarr; Delivered</strong>,
then click Update.</p>

<h2>Site Template</h2>
<p>Controls the look of the <em>entire site</em> for every visitor at once - Regular, Dark, or Retro
(including this admin panel itself, so it always matches whatever the storefront currently looks like).
Pick one and click Save Template. See the <a href="../wiki/help5.php" target="_blank">Site Look &amp;
Themes</a> help page for what each one looks like.</p>

<h2>Site Status</h2>
<p>A quick health check - shows whether the database connection, key tables, the images/videos folders,
and the login/session system are all working normally. Green means online, red means something needs
attention. Check this first if customers report something is broken.</p>

<h2>Common Tasks</h2>
<ul>
    <li><strong>A customer says they can't log in:</strong> check Manage Users to see if their account was disabled.</li>
    <li><strong>You want to run a sale:</strong> go to Manage Products and type a sale price into that
        product's row - no database access needed.</li>
    <li><strong>Something on the site looks broken:</strong> check Site Status first, then the products/orders
        tables for anything obviously missing.</li>
</ul>

<?php include 'includes/footer.php'; ?>
