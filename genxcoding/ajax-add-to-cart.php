<?php
// ajax-add-to-cart.php
// Called via fetch() from js/main.js so the "Add to Cart" button can give
// instant feedback without reloading the whole page. Falls back gracefully -
// if JS is off, the normal <form> POST on product.php still works exactly
// like before.

require_once 'config.php';
header('Content-Type: application/json');

// guests still can't add to cart - tell the browser where to send them instead
if (!isset($_SESSION['user_id'])) {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    echo json_encode([
        'success' => false,
        'redirect' => 'login.php?next=' . urlencode("product.php?id=$product_id")
    ]);
    exit;
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$chosen_option = isset($_POST['chosen_option']) ? $_POST['chosen_option'] : 'Default';
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

$result = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $product_id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$_SESSION['cart'][] = [
    'product_id' => $product_id,
    'name' => $product['name'],
    'price' => $product['price'],
    'option' => $chosen_option,
    'qty' => $qty
];

echo json_encode([
    'success' => true,
    'count' => count($_SESSION['cart'])
]);
