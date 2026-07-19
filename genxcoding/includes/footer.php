</main>

<?php
// footer.php - the shared site footer (5 columns: brand, categories,
// company links, support links, newsletter signup) plus the closing tags
// for the layout wrapper that includes/header.php opened.

// pull the category list for the "Shop by Category" footer column
$footer_categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
?>

<footer class="site-footer">
    <div class="footer-cols">
        <div>
            <h4>GenX Coding</h4>
            <p>Merch for people who debug for fun.</p>
        </div>
        <div>
            <h4>Shop by Category</h4>
            <?php while ($cat = mysqli_fetch_assoc($footer_categories)) { ?>
                <a href="<?php echo BASE_URL; ?>/category.php?id=<?php echo $cat['category_id']; ?>"><?php echo $cat['name']; ?></a><br>
            <?php } ?>
        </div>
        <div>
            <h4>Company</h4>
            <a href="<?php echo BASE_URL; ?>/about.php">About</a><br>
            <a href="<?php echo BASE_URL; ?>/contact.php">Contact</a><br>
            <a href="<?php echo BASE_URL; ?>/static/shipping.html">Shipping</a><br>
            <a href="<?php echo BASE_URL; ?>/static/privacy.html">Privacy Policy</a>
        </div>
        <div>
            <h4>Support</h4>
            <a href="<?php echo BASE_URL; ?>/wiki/help1.php">Help Wiki</a><br>
            <a href="<?php echo BASE_URL; ?>/static/faq.html">FAQ</a><br>
            <a href="<?php echo BASE_URL; ?>/static/sizing-guide.html">Sizing Guide</a><br>
            <a href="<?php echo BASE_URL; ?>/static/care-guide.html">Care Guide</a>
        </div>
        <!-- newsletter form - posts to newsletter.php, which redirects back
             to whatever page the visitor was on (see the hidden "came_from"
             field) with ?subscribed=1, which js/main.js reads to show a
             confirmation message right here without needing a full reload -->
        <div id="newsletter">
            <h4>Stay Updated</h4>
            <p>Get new arrivals and sale alerts.</p>
            <form method="post" action="<?php echo BASE_URL; ?>/newsletter.php" class="newsletter-form">
                <input type="hidden" name="came_from" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                <input type="email" name="email" placeholder="Your email" required>
                <button type="submit" class="btn">Join</button>
            </form>
        </div>
    </div>
    <p class="copyright">&copy; 2026 GenX Coding. All rights reserved.</p>
</footer>

</div><!-- /.page-wrapper -->

<?php
// cache-busting: append the file's last-modified time as a version number
// so browsers always fetch the latest JS after an update instead of a
// stale cached copy - see the matching comment in includes/header.php
$js_path = $_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/js/main.js';
$js_version = file_exists($js_path) ? filemtime($js_path) : time();
?>
<script src="<?php echo BASE_URL; ?>/js/main.js?v=<?php echo $js_version; ?>"></script>
</body>
</html>
