<?php
ob_start();
session_start();
include("../includes/connect.php");

if (!isset($_SESSION['username']) || empty($_SESSION['last_order_id'])) {
    header("Location: ../index.php");
    exit();
}

$order_id = $_SESSION['last_order_id'];

$stmt = $con->prepare("SELECT order_id, amount_due, invoice_number, order_date FROM user_orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

ob_end_clean();
include('../includes/header.php');
?>

<script>document.title = 'Order Placed'</script>

<?php include('../includes/navbar.php'); ?>

<div class="container payment-container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="payment-status-card text-center">
                <div class="success-icon mb-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="custom-heading">Order Placed Successfully!</h2>
                <p class="status-message">Thank you for your purchase. Your order has been confirmed.</p>

                <?php if ($order): ?>
                <div class="info-row">
                    <span>Order ID</span>
                    <strong>#<?php echo $order['order_id']; ?></strong>
                </div>
                <div class="info-row">
                    <span>Invoice</span>
                    <strong><?php echo $order['invoice_number']; ?></strong>
                </div>
                <div class="info-row">
                    <span>Total Amount</span>
                    <strong>$<?php echo number_format($order['amount_due'], 2); ?></strong>
                </div>
                <div class="info-row">
                    <span>Payment</span>
                    <strong>Cash on Delivery</strong>
                </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="profile.php" class="btn btn-success-custom me-2">
                        <i class="fas fa-box me-1"></i>View My Orders
                    </a>
                    <a href="../index.php" class="btn btn-outline-secondary-custom">
                        <i class="fas fa-home me-1"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
<?php include('../includes/scripts_footer.php'); ?>
