<?php
ob_start();
session_start();
include("../includes/connect.php");
include("../functions/common_function.php");

if (!isset($_SESSION['username'])) {
    header("Location: user_login.php?checkout=1");
    exit();
}

$username = $_SESSION['username'];
$stmt = $con->prepare("SELECT user_id, user_address, user_mobile FROM user_table WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows === 0) {
    header("Location: user_login.php");
    exit();
}

$user = $user_result->fetch_assoc();
$user_id = $user['user_id'];

$cart_items = [];
$total_price = 0;

$statement = $con->prepare("SELECT P.product_id, P.product_title, P.product_price, P.product_image1, C.quantity 
    FROM cart_details C 
    JOIN products P ON P.product_id = C.product_id 
    WHERE C.user_id = ?");
$statement->bind_param("i", $user_id);
$statement->execute();
$result = $statement->get_result();

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['product_price'] * $row['quantity'];
}

if (empty($cart_items)) {
    $_SESSION['toast_message'] = "Your cart is empty";
    header("Location: ../cart.php");
    exit();
}

ob_end_clean();
include('../includes/header.php');
?>

<script>document.title = 'Checkout'</script>

<?php include('../includes/navbar.php'); ?>

<div class="container my-4">
    <div class="hero-banner">Checkout</div>
</div>

<div class="container payment-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="payment-card mb-4">
                <h4 class="cart-summary-title">
                    <i class="fas fa-shopping-bag me-2"></i>Order Summary
                </h4>

                <?php foreach ($cart_items as $item): 
                    $subtotal = $item['product_price'] * $item['quantity'];
                ?>
                <div class="cart-summary-item">
                    <div class="d-flex align-items-center">
                        <img src="../images/<?php echo htmlspecialchars($item['product_image1']); ?>" 
                             alt="<?php echo htmlspecialchars($item['product_title']); ?>"
                             style="width:50px; height:50px; object-fit:cover; border-radius:6px; margin-right:12px;">
                        <div>
                            <strong><?php echo htmlspecialchars($item['product_title']); ?></strong>
                            <br>
                            <small class="text-muted">Qty: <?php echo $item['quantity']; ?> x $<?php echo $item['product_price']; ?></small>
                        </div>
                    </div>
                    <span class="fw-bold">$<?php echo $subtotal; ?>/-</span>
                </div>
                <?php endforeach; ?>

                <div class="total-price-row">
                    <span>Total Amount</span>
                    <span>$<?php echo $total_price; ?>/-</span>
                </div>
            </div>

            <div class="payment-card mb-4">
                <h4 class="cart-summary-title">
                    <i class="fas fa-location-dot me-2"></i>Shipping Details
                </h4>
                <div class="mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <p class="mb-0"><?php echo htmlspecialchars($username); ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Address</label>
                    <p class="mb-0"><?php echo htmlspecialchars($user['user_address'] ?: 'No address on file'); ?></p>
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold">Phone</label>
                    <p class="mb-0"><?php echo htmlspecialchars($user['user_mobile'] ?: 'No phone on file'); ?></p>
                </div>
            </div>

            <div class="payment-card">
                <form id="paymentForm" method="post" action="process_payment.php">
                    <input type="hidden" name="total_amount" value="<?php echo $total_price; ?>">
                    <button type="submit" name="pay_now" class="btn btn-razorpay-pay w-100">
                        <i class="fas fa-lock me-2"></i>Pay $<?php echo $total_price; ?> Now
                    </button>
                </form>
                <p class="text-center text-muted mt-3 mb-0">
                    <i class="fas fa-shield-halved me-1"></i>Your payment is secure and encrypted
                </p>
            </div>

            <div class="text-center mt-4">
                <a href="../cart.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Cart
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
<?php include('../includes/scripts_footer.php'); ?>
