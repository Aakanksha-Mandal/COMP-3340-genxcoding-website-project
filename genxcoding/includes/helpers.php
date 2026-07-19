<?php
// helpers.php - small reusable functions shared across pages.

// Builds the price HTML for a product row - if it has a sale_price that's
// lower than the regular price, shows a struck-through original price plus
// the sale price. Otherwise just shows the regular price.
function render_price($row) {
    $price = (float) $row['price'];
    $sale = isset($row['sale_price']) ? $row['sale_price'] : null;

    if ($sale !== null && (float)$sale > 0 && (float)$sale < $price) {
        return '<span class="original-price">$' . number_format($price, 2) . '</span> '
             . '<span class="price">$' . number_format((float)$sale, 2) . '</span>';
    }
    return '<span class="price">$' . number_format($price, 2) . '</span>';
}

// True if this product currently has a valid sale price - used to decide
// whether to show the little "Sale" badge on the product image.
function is_on_sale($row) {
    $price = (float) $row['price'];
    $sale = isset($row['sale_price']) ? $row['sale_price'] : null;
    return ($sale !== null && (float)$sale > 0 && (float)$sale < $price);
}
?>
