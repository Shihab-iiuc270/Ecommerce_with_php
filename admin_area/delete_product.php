<?php
include('./includes/connect.php');

if (isset($_GET['delete_products'])) {
    $delete_id = $_GET['delete_products'];

    // Prepared statement to delete product safely
    $delete_product = $con->prepare("select oi.order_id,u.track_status From
     `order_items` oi JOIN user_orders u ON oi.order_id=u.order_id 
      WHERE oi.product_id = ?");
    $delete_product->bind_param("i",$delete_id);
    $delete_product->execute();
    $result_product = $delete_product->get_result();

    if ($result_product->num_rows==0) {
        $delete_product=$con->prepare("DELETE From products
        where product_id=?");
        $delete_product->bind_param("i",$delete_id);
        $delete_product->execute();
        echo "<script>alert('Product deleted successfully!');</script>";
        echo "<script>window.location.href='index.php?view_products';</script>";
        exit();
    } 
$can_delete = true;

        while ($row = $result_product->fetch_assoc()) {
            if (strtolower($row['track_status']) != 'delivered') {
                $can_delete = false;
                break;
            }
        }

        // If any order is not delivered, block deletion
        if (!$can_delete) {
            echo "<script>alert('This product cannot be deleted because some orders are not delivered yet');</script>";
            echo "<script>window.open('index.php?view_products', '_self');</script>";
        } else {
            // All orders containing this product have been delivered, so delete it
            $stmt_delete = $con->prepare("DELETE FROM products WHERE product_id = ?");
            $stmt_delete->bind_param("i", $delete_id);
            $stmt_delete->execute();

            echo "<script>alert('Product deleted successfully');</script>";
            echo "<script>window.open('index.php?view_products', '_self');</script>";
        }
       }

?>