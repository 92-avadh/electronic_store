<?php require_once('header.php'); ?>

<?php
// Check if the product is valid or not
if( !isset($_REQUEST['id']) || !isset($_REQUEST['size']) || !isset($_REQUEST['color'])  ) {
    header('location: cart.php');
    exit;
}

$i=0;
foreach($_SESSION['cart_p_id'] as $key => $value) {
    $i++;
    $arr_cart_p_id[$i] = $value;
}

$i=0;
if(isset($_SESSION['cart_size_id'])) {
    foreach($_SESSION['cart_size_id'] as $key => $value) { $i++; $arr_cart_size_id[$i] = $value; }
}
$i=0;
if(isset($_SESSION['cart_size_name'])) {
    foreach($_SESSION['cart_size_name'] as $key => $value) { $i++; $arr_cart_size_name[$i] = $value; }
}
$i=0;
if(isset($_SESSION['cart_color_id'])) {
    foreach($_SESSION['cart_color_id'] as $key => $value) { $i++; $arr_cart_color_id[$i] = $value; }
}
$i=0;
if(isset($_SESSION['cart_color_name'])) {
    foreach($_SESSION['cart_color_name'] as $key => $value) { $i++; $arr_cart_color_name[$i] = $value; }
}
$i=0;
foreach($_SESSION['cart_p_qty'] as $key => $value) {
    $i++;
    $arr_cart_p_qty[$i] = $value;
}
$i=0;
foreach($_SESSION['cart_p_current_price'] as $key => $value) {
    $i++;
    $arr_cart_p_current_price[$i] = $value;
}
$i=0;
foreach($_SESSION['cart_p_name'] as $key => $value) {
    $i++;
    $arr_cart_p_name[$i] = $value;
}
$i=0;
foreach($_SESSION['cart_p_featured_photo'] as $key => $value) {
    $i++;
    $arr_cart_p_featured_photo[$i] = $value;
}

// Clear old session arrays
unset($_SESSION['cart_p_id']);
unset($_SESSION['cart_size_id']);
unset($_SESSION['cart_size_name']);
unset($_SESSION['cart_color_id']);
unset($_SESSION['cart_color_name']);
unset($_SESSION['cart_p_qty']);
unset($_SESSION['cart_p_current_price']);
unset($_SESSION['cart_p_name']);
unset($_SESSION['cart_p_featured_photo']);

$k=1;
for($i=1;$i<=count($arr_cart_p_id);$i++) {
    
    // Safely get size and color (if they exist)
    $current_size_id = isset($arr_cart_size_id[$i]) ? $arr_cart_size_id[$i] : '';
    $current_color_id = isset($arr_cart_color_id[$i]) ? $arr_cart_color_id[$i] : '';

    // If this is the item to delete, skip it
    if( ($arr_cart_p_id[$i] == $_REQUEST['id']) && ($current_size_id == $_REQUEST['size']) && ($current_color_id == $_REQUEST['color']) ) {
        continue;
    } else {
        // Otherwise, put it back in the cart
        $_SESSION['cart_p_id'][$k] = $arr_cart_p_id[$i];
        $_SESSION['cart_size_id'][$k] = $current_size_id;
        $_SESSION['cart_size_name'][$k] = isset($arr_cart_size_name[$i]) ? $arr_cart_size_name[$i] : '';
        $_SESSION['cart_color_id'][$k] = $current_color_id;
        $_SESSION['cart_color_name'][$k] = isset($arr_cart_color_name[$i]) ? $arr_cart_color_name[$i] : '';
        $_SESSION['cart_p_qty'][$k] = $arr_cart_p_qty[$i];
        $_SESSION['cart_p_current_price'][$k] = $arr_cart_p_current_price[$i];
        $_SESSION['cart_p_name'][$k] = $arr_cart_p_name[$i];
        $_SESSION['cart_p_featured_photo'][$k] = $arr_cart_p_featured_photo[$i];
        $k++;
    }
}

header('location: cart.php');
?>