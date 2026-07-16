<?php
// 1. Start the session and include database connection files [00:01:02]
session_start();
include('../includes/connect.php'); 
include('../functions/common_function.php'); 

// 2. Redirect to login page if user session is not set [00:01:18]
if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = "Please login to manage your orders";
    header("Location: login.php");
    exit();
}

// 3. Verify if order ID is provided and not empty in the URL parameter [00:02:09]
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    $_SESSION['error'] = "Order ID not provided";
    header("Location: profile.php");
    exit();
}

$user_id =  $_SESSION['user_id'] ??null;
$username =  $_SESSION['username'];
if(!$user_id){
    $stmt=$con->prepare("select user_id from user_table where
    username=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result=$stmt->get_result();
    if($result && $row=$result->fetch_assoc()){
        $user_id=$row['user_id'];
        $_SESSION['user_id']=$user_id;
    }
    
}

// Convert order ID from URL parameter to integer safely [00:04:06]
$order_id = (int)$_GET['order_id'];
if(!$user_id){         
    $_SESSION['toast_message']="Error.Could not retrive user data order ";
    echo "<script>window.location.href='profile.php';</script>";
    exit();
}
// 5. Query to fetch track status and associated user ID for this order [00:05:04]
$statement = $con->prepare("SELECT track_status, user_id FROM user_orders WHERE order_id = ?");
$statement->bind_param("i", $order_id);
$statement->execute();
$result = $statement->get_result();

// If no matching order record is found [00:06:46]
if ($result->num_rows == 0) {
    $_SESSION['message'] = "Order not found";
    header("Location: profile.php?orders");
    exit();
}

// Fetch the order row data [00:08:02]
$order_data = $result->fetch_assoc();
$track_status = strtolower($order_data['track_status']);
$order_user_id = $order_data['user_id'];

// 6. Access Control: Check if the order belongs to the currently logged-in user [00:09:01]
if ($order_user_id != $user_id) {
    $_SESSION['error'] = "Access denied. You do not own this order.";
    header("Location: profile.php?orders");
    exit();
}

// 7. Status Check: Only allow deletion if order is either 'pending' or 'processing' [00:09:41]
if ($track_status !== 'processing' && $track_status !== 'pending') {
    $_SESSION['toast_message'] = "Order cannot be deleted as tracking status is " . $track_status;
    header("Location: profile.php?orders");
    exit();
}
$success=true;
$stmt=$con->prepare('delete from user_orders where order_id=?');
$stmt->bind_param("i",$order_id);
$delete_main = $stmt->execute();

if (!$delete_main) {
    $success = false;
    $_SESSION['toast_message'] = "Failed to delete main order: " . $stmt->error;
}
$stmt->close();

$stmt = $con->prepare("DELETE FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$delete_details = $stmt->execute();

if (!$delete_details) {
    $success = false;
    $_SESSION['toast_message'] = "Failed to delete order items: " . $stmt->error;
}
$stmt->close();

// C. Final Response and Redirect handling [00:03:26]
if ($success) {
    $_SESSION['toast_message'] = "Success: Order ID " . $order_id . " has been deleted successfully.";
} else {
    $_SESSION['toast_message'] = "Order deletion failed.";
}

header("Location: profile.php?orders");
exit();

?>