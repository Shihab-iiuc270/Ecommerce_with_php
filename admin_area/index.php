<?php
session_start();
include('../includes/connect.php');

if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage - Control Panel</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body>

    <div class="sidebar" id="adminSidebar">
        <!-- Admin Profile Identity Wrapper -->
        <img src="./images.jpg" alt="admin profile" class="admin-profile-img mb-3">
        
        <a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="index.php?insert_product"><i class="fa-solid fa-plus"></i> Insert Product</a>
        <a href="index.php?view_products"><i class="fa-solid fa-box"></i> View Products</a>
        <a href="index.php?insert_category"><i class="fa-solid fa-folder-plus"></i> Insert Category</a>
        <a href="index.php?view_categories"><i class="fa-solid fa-list"></i> View Categories</a>
        <a href="index.php?insert_occasion"><i class="fa-solid fa-gift"></i> Insert Occasion</a>
        <a href="index.php?view_occasions"><i class="fa-solid fa-gifts"></i> View Occasions</a>
        <a href="index.php?list_orders"><i class="fa-solid fa-list-check"></i> Orders</a>
        <a href="index.php?list_users"><i class="fa-solid fa-users"></i> Users</a>
        
        <a href="admin_logout.php" style="background-color: #C0C0C0;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>

    <div class="top-nav">
        <a href="#" class="fas fa-bars sidebar-toggle" id="sidebarToggleBtn"></a>
        <h4 class="admin-name">Welcome, MSU 👋</h4>
    </div>

    <div class="main">

    <?php
    if (!isset($_GET['insert_product']) && 
        !isset($_GET['view_products']) && 
        !isset($_GET['edit_products']) && 
        !isset($_GET['delete_products']) && 
        !isset($_GET['insert_category']) && 
        !isset($_GET['view_categories']) && 
        !isset($_GET['edit_categories']) && 
        !isset($_GET['delete_category']) && 
        !isset($_GET['insert_occasion']) && 
        !isset($_GET['view_occasions']) && 
        !isset($_GET['edit_occasions']) && 
        !isset($_GET['delete_occasion']) && 
        !isset($_GET['list_users']) && 
        !isset($_GET['view_user']) && 
        !isset($_GET['delete_user']) && 
        !isset($_GET['list_orders']) && 
        !isset($_GET['view_order_details']) && 
        !isset($_GET['delete_order'])) {

        $stmt_products = $con->prepare("SELECT COUNT(*) AS total_products FROM `products`");
        $stmt_products->execute();
        $res_products = $stmt_products->get_result();
        $row_products = $res_products->fetch_assoc();
        $total_products = $row_products['total_products'];
        $stmt_products->close();

        $stmt_users = $con->prepare("SELECT COUNT(*) AS total_users FROM `user_table`");
        $stmt_users->execute();
        $res_users = $stmt_users->get_result();
        $row_users = $res_users->fetch_assoc();
        $total_users = $row_users['total_users'];
        $stmt_users->close();

        $stmt_orders = $con->prepare("SELECT COUNT(*) AS total_orders FROM `user_orders`");
        $stmt_orders->execute();
        $res_orders = $stmt_orders->get_result();
        $row_orders = $res_orders->fetch_assoc();
        $total_orders = $row_orders['total_orders'];
        $stmt_orders->close();

        $stmt_revenue = $con->prepare("SELECT SUM(amount_due) AS total_revenue FROM `user_orders` WHERE order_status = 'completed'");
        $stmt_revenue->execute();
        $res_revenue = $stmt_revenue->get_result();
        $row_revenue = $res_revenue->fetch_assoc();
        $total_revenue = $row_revenue['total_revenue'] ?? 0;
        $stmt_revenue->close();

        $users=$total_users;
        $orders=$total_orders;
        $month_labels=[];
        $month_values=[];

        $stmt_chart=$con->prepare("SELECT DATE_FORMAT(order_date, '%b') AS month, 
                           SUM(amount_due) AS total 
                    FROM `user_orders` 
                    WHERE order_status = 'completed' 
                    GROUP BY DATE_FORMAT(order_date, '%b') 
                    ORDER BY order_date DESC 
                    LIMIT 6");
    $stmt_chart->execute();
    $res_chart = $stmt_chart->get_result();
    while ($row = $res_chart->fetch_assoc()) {
        $month_labels[] = $row['month']; 
        $month_values[] = $row['total']; 
    }

    $month_labels = array_reverse($month_labels);
    $month_values = array_reverse($month_values);
    
    $stmt_chart->close();
        
    ?>
        <h3 class="mb-4"><i class="fas fa-chart-line"></i> Dashboard Overview</h3>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card-box">
                    <h6><i class="fas fa-box"></i> Total Products</h6>
                    <h2 class="text-primary"><?php echo $total_products; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box">
                    <h6><i class="fas fa-users"></i> Total Users</h6>
                    <h2 class="text-primary"><?php echo $total_users; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box">
                    <h6><i class="fas fa-shopping-cart"></i> Total Orders</h6>
                    <h2 class="text-primary"><?php echo $total_orders; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box">
                    <h6><i class="fas fa-rupee-sign"></i> Total Revenue</h6>
                    <h2 class="text-danger">$<?php echo number_format($total_revenue, 2); ?></h2>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="row">
            <!-- User vs Orders Metric Graph -->
            <div class="col-md-6 mb-4">
                <div class="card-box">
                    <h5 class="text-danger">
                        <i class="fas fa-chart-pie" style="color: var(--secondary-color);"></i> Users vs Orders
                    </h5>
                    <!-- Target Placeholder for JavaScript Graphing Initialization -->
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-box">
                    <h5 class="text-center">
                        <i class="fas fa-chart-bar" style="color: var(--secondary-color);"></i> Monthly Revenue
                    </h5>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    <?php 
        }
    ?>
    <?php 
    if (isset($_GET['insert_product'])) {
        include('insert_product.php');
    }
    if (isset($_GET['view_products'])) {
        include('view_products.php');
    }
    if (isset($_GET['edit_products'])) {
        include('edit_product.php');
    }
    if (isset($_GET['delete_products'])) {
        include('delete_product.php');
    }

    // Category Management
    if (isset($_GET['insert_category'])) {
        include('insert_category.php');
    }
    if (isset($_GET['view_categories'])) {
        include('view_categories.php');
    }
    if (isset($_GET['edit_categories'])) {
        include('edit_category.php');
    }
    if (isset($_GET['delete_category'])) {
        include('delete_category.php');
    }

    // Occasion Management
    if (isset($_GET['insert_occasion'])) {
        include('insert_occasion.php');
    }
    if (isset($_GET['view_occasions'])) {
        include('view_occasions.php');
    }
    if (isset($_GET['edit_occasions'])) {
        include('edit_occasion.php');
    }
    if (isset($_GET['delete_occasion'])) {
        include('delete_occasion.php');
    }

    // Order Management
    if (isset($_GET['list_orders'])) {
        include('list_orders.php');
    }
    if (isset($_GET['view_order_details'])) {
        include('view_order_details.php');
    }
    if (isset($_GET['delete_order'])) {
        include('delete_order.php');
    }

    // User Management
    if (isset($_GET['list_users'])) {
        include('list_users.php');
    }
    if (isset($_GET['view_user'])) {
        include('view_user.php');
    }
    if (isset($_GET['delete_user'])) {
        include('delete_user.php');
    }
    ?>
    </div>

 <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const sidebar = document.getElementById('adminSidebar');
    const toggleBtn = document.getElementById("sidebarToggleBtn");
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function(){
            sidebar.classList.toggle('active');
        });
        
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(){
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                }
            });
        });
        
        // Fixed typo here: 'documnet' -> 'document'
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
            }
        });
    }
});
</script>

<script>
  // Pie Chart Configuration
  const ctx = document.getElementById('pieChart');
  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Users', 'Orders'],
      datasets: [{
       data: [<?= (int)$users ?>, <?= (int)$orders ?>],
       backgroundColor: ['#581845', '#FFD700']
      }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    }
  });

  // Bar Chart Configuration
  new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
      labels: <?= json_encode($month_labels) ?>, // Removed extra array brackets
      datasets: [{
        label: 'Revenue ($)',
        data: <?= json_encode($month_values) ?>,
        backgroundColor: '#581845',
        borderColor: '#900C3F',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
                return '$' + value.toLocaleString(); // Fixed typo here
            }
          }
        }
      },
      plugins: {
          legend: {
              display: true,
              position: 'bottom'
          }
      }
    }
  });
</script>
</body>
</html>