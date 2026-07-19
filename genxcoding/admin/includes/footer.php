<?php
// admin/includes/footer.php - closes out the layout that admin/includes/
// header.php opened (the first closing tag below ends .container, </main>
// ends the content area). Mirrors includes/footer.php on the public site,
// just with a simpler admin-specific footer bar instead of the full
// 4-column one.
?>
</div>
</main>

<footer class="site-footer">
    <p class="copyright">GenX Coding Admin Panel &middot; <a href="<?php echo BASE_URL; ?>/index.php" style="color:inherit;">Back to live site</a></p>
</footer>

</div><!-- /.page-wrapper -->

<?php
// same cache-busting approach as the public footer.php - see the comment
// in admin/includes/header.php for why this matters
$admin_js_path = $_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/js/main.js';
$admin_js_version = file_exists($admin_js_path) ? filemtime($admin_js_path) : time();
?>
<script src="<?php echo BASE_URL; ?>/js/main.js?v=<?php echo $admin_js_version; ?>"></script>
</body>
</html>
