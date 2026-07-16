<?php
// session_start();
// ======================================
// 1. SESSION & COOKIE CONFIGURATION
// ======================================
// This must run before session_start() to allow session recovery 
// after cross-site POST redirection from SSLCommerz.
// $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
// session_set_cookie_params([
//     'lifetime' => 0,
//     'path' => '/',
//     'domain' => $_SERVER['HTTP_HOST'],
//     'secure' => $secure,
//     'httponly' => true,
//     'samesite' => $secure ? 'None' : 'Lax'
// ]);

// ======================================
// 2. DEPENDENCIES
// ======================================
require_once '../includes/connect.php';
require_once '../config/sslcommerz.php';

// Check if SSLCommerz sent the validation ID
if (!isset($_POST['val_id'])) {
    die('Invalid payment response');
}

$val_id = $_POST['val_id'];

// ======================================
// 3. SSLCOMMERZ VALIDATION API CALL
// ======================================
$validation_url = SSLC_VALIDATION_URL
    . '?val_id=' . urlencode($val_id)
    . '&store_id=' . urlencode(SSLC_STORE_ID)
    . '&store_passwd=' . urlencode(SSLC_STORE_PASSWORD)
    . '&format=json';

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $validation_url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($handle);

if (curl_errno($handle)) {
    $error_msg = curl_error($handle);
    curl_close($handle);
    die('Validation Error: ' . $error_msg);
}

curl_close($handle);
$result = json_decode($response, true);

if (!isset($result['status']) || !in_array($result['status'], ['VALID', 'VALIDATED'], true)) {
    die('Payment validation failed');
}

$transaction_id = $result['tran_id'];
$paid_amount = (float) $result['amount'];

$stmt = $con->prepare("SELECT * FROM user_orders WHERE payment_id = ?");
$stmt->bind_param('s', $transaction_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    die('Order not found');
}

if (abs((float) $order['amount_due'] - $paid_amount) > 0.01) {
    die('Payment amount mismatch');
}

$order_id = $order['order_id'];
$user_id = $order['user_id'];
$order_status = 'completed'; // Matches your ENUM column values
$track_status='pending';

$stmt = $con->prepare("UPDATE user_orders SET order_status = ?, track_status=? WHERE order_id = ?");
$stmt->bind_param('ssi', $order_status,$track_status, $order_id);
$stmt->execute();

// Clear the cart for this user (safely using database retrieved user_id)
$stmt = $con->prepare("DELETE FROM cart_details WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
     <link rel="stylesheet" href="../style.css">

</head>
<body>
    <div class="container py-5">
    <div class="card shadow border-0">
        <div class="card-body text-center p-5">
            <h1 class="text-success">Payment Successful</h1>
            <p class="fs-5">Your order has been placed successfully.</p>
            
            <p>Order ID: <strong><?php echo (int)$order_id; ?></strong></p>
            <p>Transaction ID: <strong><?php echo htmlspecialchars($transaction_id); ?></strong></p>

            <div class="mt-4">
                <a href="../index.php" class="btn btn-primary me-2">Continue Shopping</a>
                <a href="profile.php?orders" class="btn btn-success-custom">See order</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php 
// Include footer if you have one, or just close tags
// include("../includes/footer.php"); 
?>