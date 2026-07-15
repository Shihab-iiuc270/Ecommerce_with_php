<?php
ob_start();
session_start();
include("./includes/connect.php");
include("./functions/common_function.php");
$user_id = null;
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $stmt = $con->prepare("select user_id from `user_table`
   where username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $user_id = $user_data['user_id'];
    }
}
$total_price = 0;
$cart_items = [];

if (isset($user_id)) {
    $statement = $con->prepare("select P.product_id, P.product_title, P.product_price, P.product_image1, C.quantity 
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
} else if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));

    $query = "select * FROM products WHERE product_id IN ($ids)";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $_SESSION['cart'][$row['product_id']];

        $cart_items[] = $row;
        $total_price += $row['product_price'] * $row['quantity'];
    }
}
//update cart
if (isset($_POST['update_cart']) && isset($_POST['qty'])) {
    foreach ($_POST['qty'] as $pid => $qty) {
        $pid = (int)$pid;
        $qty = (int)$qty;

        if ($qty <= 0) continue;
        if ($user_id) {
            $stmt = $con->prepare("UPDATE cart_details SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $qty, $user_id, $pid);
            $stmt->execute();
        } else {
            $_SESSION['cart'][$pid] = $qty;
        }
    }
    $_SESSION['toast_message'] = "cart updated successfully";
    header("Location: cart.php");
    exit();
}
if (isset($_GET['delete_item'])) {
    $pid = (int)$_GET['delete_item'];
    if ($user_id) {
        $stmt = $con->prepare("delete from cart_details where
     user_id=? and product_id=?");
        $stmt->bind_param("ii", $user_id, $pid);
        $stmt->execute();
    } else {
        unset($_SESSION['cart'][$pid]);
    }
    $_SESSION['toast_message'] = "Item deleted successfully";
    header("Location: cart.php");
    exit();
}

if (isset($_POST['delete_all_cart']) && isset($_POST['remove'])) {
    foreach ($_POST['remove'] as $pid) {
        $pid = (int)$pid;
        if ($user_id) {
            $stmt = $con->prepare("delete from cart_details where
     user_id=? and product_id=?");
            $stmt->bind_param("ii", $user_id, $pid);
            $stmt->execute();
        } else {
            unset($_SESSION['cart'][$pid]);
        }
    }
    $_SESSION['toast_message'] = "Selected Item deleted successfully";
    header("Location: cart.php");
    exit();
}

ob_end_clean();
include("./includes/header.php");
// cart();
?>
<script>
    document.title = 'E-Cart'
</script>

<!-- navbar -->
<?php include("./includes/navbar.php");
?>
<div class="container my-4">
    <div class="hero-banner">Shopping Cart</div>
</div>
<div class="container mb-5">
    <div class="row">
        <form action="cart.php" method="post">
            <?php if(!empty($cart_items)) :?>
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Product Title</th>
                        <th>Product Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php
                foreach ($cart_items as $item) {
                    $product_id = $item['product_id'];
                    $product_title = $item['product_title'];
                    $product_price = $item['product_price'];
                    $quantity = $item['quantity'];
                    $product_image1 = $item['product_image1'];

                    $subtotal = $product_price * $quantity;

                    echo "<tr>
        <td><input type='checkbox' name='remove[]' value='$product_id'></td>
        <td>$product_title</td>
        <td><img src='images/$product_image1' alt='$product_title' style='width:50px;'></td>
        <td><input type='number' min='1' class='form-control w-50 mx-auto text-center' name='qty[$product_id]' value='$quantity'></td>
        <td>$product_price/-</td>
        <td>$subtotal/-</td>
        <td>
            <input type='submit' class='btn btn-update-custom-maroon' name='update_cart'value='Update'>
            <a href='cart.php?delete_item=$product_id' class='btn btn-danger btn-sm'>Delete</a>
        </td>
    </tr>";
                }
                ?>
            </table>
            <div class="d-flex justify-content-between align-items-center flex-wrap mt-4">
                <div class="col-md-6 mb-3">
                    <h4 class="text-custom-maroon">Total amount:
                        <span class="text-danger">$<?php echo $total_price ?></span>
                    </h4>
                    <div class="mt-3">
                        <a href="index.php" class="btn btn-outline-secondary me-2">Continue Shopping</a>
                        <?php if (isset($_SESSION['username'])) : ?>
                            <a href="users_area/checkout.php" class="btn btn-custom">Proceed to Checkout</a>

                        <?php else : ?>
                            <a href="users_area/user_login.php?checkout=1" class="btn btn-custom">Login to Checkout</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6 text-end mb-3">
                    <input type="submit" value="Delete Selected" name="delete_all_cart" class="btn btn-remove-custom">
                </div>
            </div>
            <?php else:?>
            <div class="col-12 text-center">
                <h4 class="text-muted">No items in your cart</h4>
                <a href="index.php" class="btn btn-custom mt-3">Explore Products</a>
            </div>
            <?php endif;?>
        </form>
    </div>
</div>


<?php include("./includes/footer.php") ?>
<?php include("./includes/scripts_footer.php") ?>