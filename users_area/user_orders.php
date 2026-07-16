<h3 class="text-custom-maroon mb-4 text-center">My Order History</h3>

<?php
// session_start();
// Query to select all user orders for the current user ID
$statement = $con->prepare("SELECT * FROM user_orders WHERE user_id = ? ORDER BY order_id DESC");
$statement->bind_param("i", $user_id);
$statement->execute();
$orders_result = $statement->get_result();

if (!$orders_result) {
    echo "<p class='alert alert-danger'>Error fetching orders: " . ($con->error) . "</p>";
}

// Check if the user has no orders placed
if ($orders_result->num_rows == 0) {
    echo "
    <div class='text-center mt-5'>
        <h4 class='text-secondary'>You haven't placed any orders yet.</h4>
        <p>Start exploring our unique collection of gifts.</p>
        <a href='../display_all.php' class='btn btn-custom mt-3'><i class='fas fa-gift me-2'></i>Shop Now</a>
    </div>
    ";
    exit();
}
?>

<!-- Orders Table -->
<div class="table-responsive mt-5">
    <table class="table table-bordered table-hover">
        <thead class="bg-custom-maroon text-light">
            <tr>
                <th style="width: 5%">SI No.</th>
                <th style="width: 8%">Order ID</th>
                <th style="width: 15%">Invoice No.</th>
                <th style="width: 12%">Total Items</th>
                <th style="width: 10%">Amount Due</th>
                <th style="width: 10%">Date</th>
                <th style="width: 12%">Order Status</th>
                <th style="width: 15%">Tracking Status</th>
                <th style="width: 20%">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            while ($row = $orders_result->fetch_assoc()) {
                $order_id = htmlspecialchars($row['order_id']);
                $order_status = strtolower($row['order_status']);
                $track_status = strtolower($row['track_status']);
                
                // Determine CSS badge classes for Order Status using Match
                $status_class = match ($order_status) {
                    'pending' => 'bg-secondary',
                    'completed' => 'bg-success',
                    'cancelled' => 'bg-danger',
                    default => 'bg-info'
                };

                // Determine CSS badge classes for Tracking Status using Match
                $track_class = match ($track_status) {
                    'pending', 'processing' => 'bg-warning text-dark',
                    'shipped' => 'bg-primary',
                    'out for delivery' => 'bg-info',
                    'delivered' => 'bg-success',
                    default => 'bg-secondary'
                };

                // Logic to check if an order is deletable (only allowed when pending or processing)
                $can_delete = ($track_status == 'processing' || $track_status == 'pending');
                $tooltip_text = htmlspecialchars(ucfirst($track_status)) . " orders cannot be deleted";

                echo "
                <tr>
                    <td class='text-center'>$counter</td>
                    <td class='text-center'>$order_id</td>
                    <td class='text-center'>" . htmlspecialchars($row['invoice_number']) . "</td>
                    <td class='text-center'>" . htmlspecialchars($row['total_products']) . "</td>
                    <td class='text-center'><strong>₹" . number_format($row['amount_due'], 2) . "</strong></td>
                    <td class='text-center'>" . htmlspecialchars($row['order_date']) . "</td>
                    <td class='text-center'>
                        <span class='badge $status_class p-2'>" . ucfirst($order_status) . "</span>
                    </td>
                    <td class='text-center'>
                        <span class='badge $track_class p-2'>" . ucfirst($track_status) . "</span>
                    </td>
                    <td class='text-center'>
                        <a href='view_order.php?order_id=$order_id' class='btn btn-sm btn-info order-btn me-1'>
                            <i class='fas fa-eye me-1'></i>View
                        </a>";

                        if ($can_delete) {
                            echo "
                            <a href='delete_order.php?order_id=$order_id' class='btn btn-sm btn-danger order-btn' onclick=\"return confirm('Are you sure you want to delete this order?');\">
                                <i class='fa fa-trash-alt me-1'></i>Delete
                            </a>";
                        } else {
                            echo "
                            <span data-bs-toggle='tooltip' data-bs-placement='top' title='{$tooltip_text}'>
                                <button class='btn btn-sm btn-secondary order-btn' disabled>
                                    <i class='fas fa-ban me-1'></i>Delete
                                </button>
                            </span>";
                        }
                echo "
                    </td>
                </tr>";
                $counter++;
            }
            ?>
        </tbody>
    </table>
</div>