<?php
session_start();
include('../includes/connect.php');
include('../functions/common_function.php');
// Check if order_id is missing OR if it is NOT a valid integer
if (!isset($_GET['order_id']) || !filter_var($_GET['order_id'], FILTER_VALIDATE_INT)) {
    $_SESSION['toast_message'] = "Invalid order Id specified";
    echo "<script>window.location.href='profile.php?orders';</script>";
    exit();
}
$username = $_SESSION['username'];
// echo $username;
// exit();
$order_id = (int)($_GET['order_id']); 
$user_id=$_SESSION['user_id'] ?? null;
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
if(!$user_id){         
    $_SESSION['toast_message']="Error.Could not retrive user data order ";
    echo "<script>window.location.href='profile.php';</script>";
    exit();
}
$stmt=$con->prepare("select * from user_orders where order_id=?
AND user_id =?");
$stmt->bind_param("ii",$order_id,$user_id);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows==0){
    $_SESSION['toast_message']="Access denied.The order was not found or order does not belong to your account"
;
echo "<script>window.location.href='profile.php?orders';</script>";
    exit();
    }
$order_data=$result->fetch_assoc();
$product_query = $con->prepare("SELECT 
    oi.quantity, 
    oi.product_id, 
    p.product_title, 
    p.product_image1, 
    p.product_price 
    FROM order_items oi 
    INNER JOIN products p ON oi.product_id = p.product_id 
    WHERE oi.order_id = ?");
$product_query->bind_param("i", $order_id);
$product_query->execute();
$product_result = $product_query->get_result();

$product_items = [];
if ($product_result) {
    while ($row = $product_result->fetch_assoc()) {
        $product_items[] = $row;
    }
}
if (empty($product_items) && $order_data['total_products'] > 0) {
    $_SESSION['toast_message']="Warning: Product details could not be loaded for this order.";
    echo "<script>window.location.href='profile.php?orders';</script>";
    exit();
}
$page_title = "Order Details - Invoice #" . htmlspecialchars($order_data['invoice_number']);
include('../includes/header.php');
include('../includes/navbar.php');
?>

<div class="container view-order-container">
    <!-- Page Header -->
    <h3 class="text-center mb-4 text-custom-maroon">Order Details - Invoice #<?php echo htmlspecialchars($order_data['invoice_number']); ?></h3>
    
    <!-- Order Summary Card -->
    <div class="order-card-detail order-summary">
        <h4 class="custom-heading">
            <i class="fas fa-file-invoice me-2"></i>Order Summary
        </h4>
        <div class="row">
            <!-- Left Summary Column -->
            <div class="col-md-6">
                <div class="summary-item">
                    <strong class="summary-label">Order ID:</strong> #<?php echo (int)$order_data['order_id']; ?>
                </div>
                <div class="summary-item">
                    <strong class="summary-label">Order Date:</strong> <?php echo htmlspecialchars($order_data['order_date']); ?>
                </div>
                <div class="summary-item">
                    <strong class="summary-label">Total Amount:</strong> <strong>BDT <?php echo number_format($order_data['amount_due'], 2); ?></strong>
                </div>
                <div class="summary-item">
                    <strong class="summary-label">Total Products:</strong> <?php echo (int)$order_data['total_products']; ?>
                </div>
            </div>
            
            <!-- Right Summary Column -->
            <div class="col-md-6">
                <div class="summary-item">
                    <strong class="summary-label">Payment Mode:</strong> <?php echo htmlspecialchars($order_data['payment_mode'] ?? 'Online'); ?>
                </div>
                <?php 
                $order_status=strtolower($order_data['order_status']) ;
                $status_class=($order_status=='completed')?'bg-success':
                (($order_status=='pending')?'bg-secondary':'bg-danger');
                $track_status=(strtolower($order_data['track_status'])=='delivered')?'bg-success':'bg-info text-black';
                ?>

                <div class="summary-item">
                    <strong>Payment Status: </strong> 
                    <span class="badge <?php echo $status_class?>">
                        <?php echo ucfirst(htmlspecialchars($order_data['order_status'])); ?>
                    </span>
                </div>
                <div class="summary-item">
                    <strong>Tracking Status: </strong> 
                    <span class="badge <?php echo $track_status?>">
                        <?php echo ucfirst(htmlspecialchars($order_data['track_status'])); ?>
                    </span>
                </div>
                <div class="summary-item">
                    <strong>Payment ID: </strong> <?php echo htmlspecialchars($order_data['payment_id'] ?? 'N/A'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table Card -->
    <div class="order-card-detail product-table">
        <h4 class="custom-heading">
            <i class="fas fa-boxes me-2"></i>Items in this Order
        </h4>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-header-custom text-white">
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price (BDT)</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($product_items as $item): ?>
                    <?php $subtotal = $item['product_price'] * $item['quantity']; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_title']); ?></td>
                        <td>
                            <img src="../images/<?php echo htmlspecialchars($item['product_image1']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['product_title']); ?>" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                                 onerror="this.src='../images/download.png'">
                        </td>
                        <td>BDT <?php echo number_format($item['product_price'], 2); ?></td>
                        <td><?php echo (int)$item['quantity']; ?></td>
                        <td>BDT <?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Navigation Action -->
    <div class="text-center mb-5">
        <a href="profile.php?orders" class="btn btn-secondary btn-custom-view">
            <i class="fas fa-arrow-left"></i> Back to Order History
        </a>
    </div>
</div>

<?php
include('../includes/footer.php');
include('../includes/scripts_footer.php');
?>