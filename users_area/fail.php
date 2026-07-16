<?php

require_once '../includes/connect.php';


if (isset($_POST['tran_id'])) {

    $transaction_id = $_POST['tran_id'];

    $status = 'pending';


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

    <title>Payment Failed</title>

</head>

<body>

    <h1>Payment Failed</h1>

    <p>
        Your payment could not be completed.
    </p>

    <a href="checkout.php">
        Try Again
    </a>

</body>

</html>