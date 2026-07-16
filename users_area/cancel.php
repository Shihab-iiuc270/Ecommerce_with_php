<?php

require_once '../includes/connect.php';


if (isset($_POST['tran_id'])) {

    $transaction_id = $_POST['tran_id'];

    $status = 'cancelled';


    $stmt = $con->prepare("
        UPDATE user_orders
        SET order_status = ?
        WHERE payment_id = ?
    ");

    $stmt->bind_param(
        'ss',
        $status,
        $transaction_id
    );

    $stmt->execute();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Payment Cancelled</title>

</head>

<body>

    <h1>Payment Cancelled</h1>

    <p>
        You cancelled the payment.
    </p>

    <a href="checkout.php">
        Return to Checkout
    </a>

</body>

</html>