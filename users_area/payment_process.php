<?php

session_start();

require_once '../includes/connect.php';
require_once '../config/sslcommerz.php';


if (!isset($_SESSION['username'])) {
    header('Location: ../user_area/user_login.php');
    exit;
}


if (!isset($_POST['pay_now'])) {
    header('Location: checkout.php');
    exit;
}


$username = $_SESSION['username'];




$stmt = $con->prepare("
    SELECT *
    FROM user_table
    WHERE username = ?
");

$stmt->bind_param('s', $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();


if (!$user) {
    die('User not found');
}


$user_id = $user['user_id'];




$stmt = $con->prepare("
    SELECT
        cart_details.product_id,
        cart_details.quantity,
        products.product_price
    FROM cart_details
    INNER JOIN products
        ON cart_details.product_id = products.product_id
    WHERE cart_details.user_id = ?
");

$stmt->bind_param('i', $user_id);
$stmt->execute();

$result = $stmt->get_result();


$cart_items = [];
$total_price = 0;
$total_products = 0;


while ($row = $result->fetch_assoc()) {

    $cart_items[] = $row;

    $subtotal = $row['product_price'] * $row['quantity'];

    $total_price += $subtotal;
    $total_products += $row['quantity'];
}


if (empty($cart_items)) {
    die('Your cart is empty');
}




$transaction_id = 'TXN_' . uniqid();

$invoice_number = mt_rand(100000, 999999);



$order_status = 'pending';
$track_status = 'processing';
$payment_mode = 'SSLCOMMERZ';


$stmt = $con->prepare("
    INSERT INTO user_orders
    (
        user_id,
        amount_due,
        invoice_number,
        total_products,
        payment_id,
        payment_mode,
        order_date,
        order_status,
        track_status
    )
    VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)
");


$stmt->bind_param(
    'idiissss',
    $user_id,
    $total_price,
    $invoice_number,
    $total_products,
    $transaction_id,
    $payment_mode,
    $order_status,
    $track_status
);


$stmt->execute();


$order_id = $con->insert_id;



$stmt = $con->prepare("
    INSERT INTO order_items
    (
        order_id,
        product_id,
        quantity,
        price
    )
    VALUES (?, ?, ?, ?)
");


foreach ($cart_items as $item) {

    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['product_price'];

    $stmt->bind_param(
        'iiid',
        $order_id,
        $product_id,
        $quantity,
        $price
    );

    $stmt->execute();
}




$post_data = [];

$post_data['store_id'] = SSLC_STORE_ID;
$post_data['store_passwd'] = SSLC_STORE_PASSWORD;

$post_data['total_amount'] = $total_price;
$post_data['currency'] = 'BDT';

$post_data['tran_id'] = $transaction_id;


$post_data['success_url'] =
    'http://localhost/ecommerce/users_area/success.php';

$post_data['fail_url'] =
    'http://localhost/ecommerce/users_area/fail.php';

$post_data['cancel_url'] =
    'http://localhost/ecommerce/users_area/cancel.php';


// CUSTOMER INFORMATION

$post_data['cus_name'] = $username;

$post_data['cus_email'] =
    $user['user_email'];

$post_data['cus_add1'] =
    $user['user_address'];

$post_data['cus_city'] = 'Chattogram';

$post_data['cus_country'] = 'Bangladesh';

$post_data['cus_phone'] =
    $user['user_mobile'];


// SHIPPING INFORMATION

$post_data['shipping_method'] = 'YES';

$post_data['ship_name'] = $username;

$post_data['ship_add1'] =
    $user['user_address'];

$post_data['ship_city'] = 'Chattogram';

$post_data['ship_country'] = 'Bangladesh';
$post_data['ship_state'] = 'Chattogram';
$post_data['ship_postcode'] = '4000';  

// PRODUCT INFORMATION

$post_data['product_name'] = 'Ecommerce Products';

$post_data['product_category'] = 'General';

$post_data['product_profile'] = 'general';


// SAVE ORDER ID

$post_data['value_a'] = $order_id;




$handle = curl_init();


curl_setopt(
    $handle,
    CURLOPT_URL,
    SSLC_PAYMENT_URL
);

curl_setopt(
    $handle,
    CURLOPT_POST,
    true
);

curl_setopt(
    $handle,
    CURLOPT_POSTFIELDS,
    $post_data
);

curl_setopt(
    $handle,
    CURLOPT_RETURNTRANSFER,
    true
);


$response = curl_exec($handle);


if (curl_errno($handle)) {

    echo 'cURL Error: ' . curl_error($handle);

    curl_close($handle);

    exit;
}


curl_close($handle);


$result = json_decode($response, true);


if (
    isset($result['status']) &&
    $result['status'] === 'SUCCESS'
) {

    header(
        'Location: ' . $result['GatewayPageURL']
    );

    exit;

}


echo '<pre>';
print_r($result);
echo '</pre>';