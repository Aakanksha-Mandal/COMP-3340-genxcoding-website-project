<?php
// session-status.php
// A tiny JSON endpoint that reports whether the visitor is currently
// logged in, their username, and their cart count. The PHP pages don't
// need this - they already check $_SESSION directly when rendering the
// header. This exists specifically for the genuinely-static HTML pages in
// /static (faq.html, shipping.html, etc.), which have no PHP at all and
// therefore can't check $_SESSION themselves. js/main.js calls this on
// page load for those pages and rewrites the header's account links to
// match, so a logged-in visitor doesn't see "Login/Register" on a static
// page just because that page can't check their session on its own.

require_once 'config.php';
header('Content-Type: application/json');

echo json_encode([
    'loggedIn' => isset($_SESSION['user_id']),
    'username' => isset($_SESSION['username']) ? $_SESSION['username'] : null,
    'cartCount' => isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0
]);
