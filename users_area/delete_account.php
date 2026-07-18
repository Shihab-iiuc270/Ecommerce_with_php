<?php
include('../includes/connect.php');
if (!isset($_SESSION['username'])) {
    $_SESSION['toast_message'] = "Please login to access this page";
    echo "<script>window.location.href='user_login.php';</script>";
    exit();
}

$username = $_SESSION['username'];

$query = "SELECT user_id, user_password FROM `user_table` WHERE username = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $_SESSION['toast_message'] = "User data not found. Please try again.";
    session_destroy();
    echo "<script>window.location.href='user_login.php';</script>";
    exit();
}
if (isset($_POST['delete']) && isset($user)) {
    $entered_password = $_POST['confirm_password'];
    $hashed_password = $user['user_password'];
    $user_id= $user['user_id'];
    if (password_verify($entered_password, $hashed_password)) {
        $get_orders = $con->prepare("SELECT order_id FROM `user_orders` WHERE user_id = ?");
        $get_orders->bind_param("i", $user_id);
        $get_orders->execute();
        $orders_result = $get_orders->get_result();
        while ($order = $orders_result->fetch_assoc()) {
            $order_id = $order['order_id'];
            $delete_items = $con->prepare("DELETE FROM `order_items` WHERE order_id = ?");
            $delete_items->bind_param("i", $order_id);
            $delete_items->execute();
        }
        $delete_orders = $con->prepare("DELETE FROM `user_orders` WHERE user_id = ?");
        $delete_orders->bind_param("i", $user_id);
        $delete_orders->execute();

        $delete_query = "DELETE FROM `user_table` WHERE username = ?";
        $delete_user_stmt = $con->prepare($delete_query);
        $delete_user_stmt->bind_param("s", $username);
        $result_delete = $delete_user_stmt->execute();

        if ($result_delete) {
            session_unset();
            session_destroy();
            
            echo "<script>alert('Account deleted successfully!');</script>";
            echo "<script>window.location.href='../index.php';</script>";
            exit();
        } else {
            $_SESSION['toast_message'] = "Database error while deletion.";
            echo "<script>window.location.href='profile.php';</script>";
            exit();
        }

    } else {
        $_SESSION['toast_message'] = "Passwords do not match.";
        echo "<script>window.location.href='profile.php?delete_account';</script>";
        exit();
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="registration-card w-50 m-auto">
        
        <!-- Section Header Title -->
        <h3 class="text-danger text-center mb-4">Permanently Delete Account</h3>
        
        <!-- Warning Message Section -->
        <p class="text-center text-secondary fw-normal mb-4">
           Warning: This action is irreversible. All your order history and data will be gone.
        </p>
        
        <form method="post">
                        <div class="mb-4">
                <label class="form-label">
                    <strong>Confirm your password</strong>
                </label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            
            <input type="submit" name="delete" class="btn btn-danger w-100 mb-3" value="Delete my Account">
            
            <a href="profile.php" class="btn btn-secondary w-100">Cancel</a>
            
        </form>
        
    </div>
</div>
