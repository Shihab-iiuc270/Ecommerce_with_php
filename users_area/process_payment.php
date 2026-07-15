<?php
ob_start();
session_start();
include("../includes/connect.php");

/*
|--------------------------------------------------------------------------
| Process Payment
|--------------------------------------------------------------------------
|
| 1. Verify user is logged in and cart is not empty
| 2. Create order in `user_orders` table
| 3. Save order items in `order_items` table
| 4. Clear the user's cart
| 5. Redirect to success page
|
*/

// ── Check Login ──────────────────────────────────────────────
if (!isset($_SESSION['username'])) {
    $_SESSION['toast_message'] = "Please login to continue";
    header("Location: user_login.php?checkout=1");
    exit();
}

$username = $_SESSION['username'];
$stmt = $con->prepare("SELECT user_id, user_email, user_address, user_mobile FROM user_table WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows === 0) {
    header("Location: user_login.php");
    exit();
}

$user = $user_result->fetch_assoc();
$user_id = $user['user_id'];

// ── Fetch Cart Items ─────────────────────────────────────────
$cart_items = [];
$total_price = 0;

$cart_stmt = $con->prepare("SELECT P.product_id, P.product_title, P.product_price, P.product_image1, C.quantity 
    FROM cart_details C 
    JOIN products P ON P.product_id = C.product_id 
    WHERE C.user_id = ?");
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

while ($row = $cart_result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['product_price'] * $row['quantity'];
}

if (empty($cart_items)) {
    $_SESSION['toast_message'] = "Your cart is empty";
    header("Location: ../cart.php");
    exit();
}

// ── Generate Invoice Number ──────────────────────────────────
$invoice_number = "INV-" . date('Ymd') . "-" . strtoupper(substr(uniqid(), -6));

// ── Save Order ───────────────────────────────────────────────
$total_products = count($cart_items);

$order_stmt = $con->prepare("INSERT INTO user_orders 
    (user_id, amount_due, invoice_number, total_prodcuts, payment_id, payment_mode, order_date, order_status, track_status) 
    VALUES (?, ?, ?, ?, '', 'Cash on Delivery', NOW(), 'complete', 'order_placed')");
$order_stmt->bind_param("idsi", $user_id, $total_price, $invoice_number, $total_products);
$order_stmt->execute();
$order_id = $con->insert_id;

// ── Save Order Items ─────────────────────────────────────────
$item_stmt = $con->prepare("INSERT INTO order_items 
    (order_id, product_id, quantity, price) 
    VALUES (?, ?, ?, ?)");

foreach ($cart_items as $item) {
    $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['product_price']);
    $item_stmt->execute();
}

// ── Clear Cart ───────────────────────────────────────────────
$clear_stmt = $con->prepare("DELETE FROM cart_details WHERE user_id = ?");
$clear_stmt->bind_param("i", $user_id);
$clear_stmt->execute();

unset($_SESSION['cart']);

// ── Redirect to Success Page ─────────────────────────────────
$_SESSION['last_order_id'] = $order_id;
header("Location: payment_success.php");
exit();
