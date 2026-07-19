<?php
require_once '../config.php';
$pageTitle = "Help - Technical Documentation";
$pageDescription = "Technical documentation for GenX Coding - database design, front-end architecture, and code structure.";
$pageKeywords = "technical documentation, database design, front-end architecture, PHP MySQL documentation";
$helpLink = 'wiki/help7.php';
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

<h2 id="technical-docs">7. Technical Documentation</h2>
<p>This page is written for developers, instructors, or anyone evaluating the code, rather than for
regular shoppers. It documents how the site is built: the database design, the front-end architecture,
and where to find comments in the code itself.</p>

<h3 id="site-architecture">Site Architecture</h3>
<p>The site is organized like this:</p>
<ul>
    <li><strong>Root PHP files</strong> (<code>index.php</code>, <code>products.php</code>, <code>cart.php</code>, etc.)
        - one file per public-facing page.</li>
    <li><strong><code>/includes</code></strong> - <code>header.php</code> and <code>footer.php</code> are included
        at the top/bottom of every public page so the nav, page-wrapper layout, and SEO meta tags only need
        to be written once. <code>helpers.php</code> holds small shared functions like <code>render_price()</code>.</li>
    <li><strong><code>/admin</code></strong> - a separate mini-application for store administration, gated by
        its own <code>admin_id</code> session check on every page. <code>admin/includes/header.php</code> and
        <code>footer.php</code> mirror the public site's header/footer almost exactly (same CSS classes, same
        theme-detection logic, same cache-busting) so the admin panel always looks like part of the same site
        instead of a separate, mismatched tool - it even follows whichever of the 3 templates is currently
        active site-wide.</li>
    <li><strong><code>/wiki</code></strong> - this help system (7 pages).</li>
    <li><strong><code>/static</code></strong> - genuine static HTML pages (no PHP at all) that still share the
        site's CSS so they look identical to the dynamic pages.</li>
    <li><strong><code>/css</code></strong> - three template stylesheets. <code>regular.css</code> defines every
        layout rule using CSS custom properties (variables) for color; <code>dark.css</code> and
        <code>retro.css</code> just <code>@import</code> that file and override the color variables, which is
        why editing layout only has to happen in one place.</li>
    <li><strong><code>/js/main.js</code></strong> - one shared JS file: mobile menu toggle, the AJAX
        add-to-cart request, and the newsletter "subscribed" confirmation.</li>
</ul>

<h3 id="code-comments">Where the Code Comments Live</h3>
<p>Every PHP, CSS, and JS file in the project has inline comments explaining what each block does. A few
good starting points if you want to see the commenting style used throughout:</p>
<ul>
    <li><code>config.php</code> - explains the <code>BASE_URL</code> setup and why it exists.</li>
    <li><code>cart.php</code> - explains why the "remove item" logic has to run before the header include.</li>
    <li><code>admin/products.php</code>, <code>admin/users.php</code>, <code>admin/orders.php</code> - each
        admin action is commented with what it does and which form on the page triggers it.</li>
    <li><code>css/regular.css</code> - the CSS variable system and responsive breakpoints are commented at
        the top of each section.</li>
    <li><code>js/main.js</code> - each feature (mobile menu, AJAX cart, scroll fix) is commented separately.</li>
</ul>

<h3 id="database-design">Database Design</h3>
<p>The database has 9 tables. Full column definitions live in <code>database.sql</code> at the project root
(with inline comments on every non-obvious column) - this page summarizes what each table is for and how
they connect.</p>

<table class="cart-table">
<tr><th>Table</th><th>Purpose</th><th>Key Relationships</th></tr>
<tr>
    <td><code>users</code></td>
    <td>Registered accounts. Stores a hashed password, whether the account is an admin, and whether it's
        active or disabled.</td>
    <td>Referenced by <code>orders</code> and <code>reviews</code>.</td>
</tr>
<tr>
    <td><code>categories</code></td>
    <td>The 6 product categories shown as filter pills and in the footer.</td>
    <td>Referenced by <code>products.category_id</code>.</td>
</tr>
<tr>
    <td><code>products</code></td>
    <td>The 20-item catalogue. Includes an optional <code>sale_price</code> used to show the "Sale" badge
        when it's lower than <code>price</code>.</td>
    <td>Belongs to a <code>category</code>; referenced by <code>product_options</code>,
        <code>order_items</code>, and <code>reviews</code>.</td>
</tr>
<tr>
    <td><code>product_options</code></td>
    <td>Each row is one option value (e.g. "Size: Large") for one product. A product with a Size and a
        Color dropdown has multiple rows here, grouped by <code>option_name</code> when displayed.</td>
    <td>Belongs to a <code>product</code> (cascades on delete).</td>
</tr>
<tr>
    <td><code>orders</code></td>
    <td>One row per completed checkout, with a running <code>status</code> (Pending/Shipped/Delivered)
        the admin updates.</td>
    <td>Belongs to a <code>user</code>; has many <code>order_items</code>.</td>
</tr>
<tr>
    <td><code>order_items</code></td>
    <td>The individual line items within an order (product, chosen option, quantity, price at time of
        purchase).</td>
    <td>Belongs to an <code>order</code> and a <code>product</code>.</td>
</tr>
<tr>
    <td><code>reviews</code></td>
    <td>Star ratings + comments left on product pages.</td>
    <td>Belongs to a <code>product</code> and a <code>user</code>.</td>
</tr>
<tr>
    <td><code>site_settings</code></td>
    <td>Key/value settings table - currently holds one row (<code>site_template</code>) controlling which
        of the 3 CSS templates is active site-wide.</td>
    <td>Read by <code>includes/header.php</code> on every page load.</td>
</tr>
<tr>
    <td><code>newsletter_subscribers</code></td>
    <td>Email addresses collected from the footer newsletter form.</td>
    <td>Standalone - no foreign keys.</td>
</tr>
</table>

<h3 id="frontend-structure">Front-End Structure</h3>
<ul>
    <li><strong>Templates:</strong> 3 CSS files (Regular, Dark, Retro) selected site-wide by the admin
        (see <a href="help5.php">help page 5</a>). Regular additionally auto-adapts to a visitor's OS
        dark-mode setting via the CSS <code>prefers-color-scheme</code> media query.</li>
    <li><strong>Responsive design:</strong> a single breakpoint at 760px collapses the nav into a hamburger
        menu (toggled by <code>js/main.js</code>) and switches the product grid to fewer columns.</li>
    <li><strong>Forms:</strong> dynamic forms appear on at least add-to-cart (product.php), checkout,
        login, register, contact, search, and the newsletter signup - all client-validated with HTML5
        <code>required</code>/<code>type="email"</code> attributes and server-validated again in PHP.</li>
    <li><strong>Keeping static pages in sync with login state:</strong> the pages in <code>/static</code>
        have no PHP, so they can't check <code>$_SESSION</code> to know if a visitor is logged in. On page
        load, <code>js/main.js</code> calls <code>session-status.php</code> (a small JSON endpoint) and, only
        on those static pages, rewrites the header's account links to match the real session - without
        needing any PHP in the static page itself.</li>
    <li><strong>Multimedia:</strong> an embedded Google Map (contact.php), a bar chart (admin dashboard),
        product images, and a demo video on the homepage.</li>
</ul>
</div>
<?php include '../includes/footer.php'; ?>
