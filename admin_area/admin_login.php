<?php
session_start();
include('../includes/connect.php');
if (isset($_POST['admin_login'])) {
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];
    $select_query = "SELECT * FROM `admin_table` WHERE admin_name = ?";
    $stmt = $con->prepare($select_query);
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row_data = $result->fetch_assoc();
        $hashed_password = $row_data['admin_password'];
        if (password_verify($admin_password, $hashed_password)) {
            $_SESSION['admin_username'] = $admin_username;
            
            echo "<script>
                alert('Login successful! Welcome to dashboard.');
                window.location.href='index.php';
            </script>";
        } else {
            echo "<script>
                alert('Passwords do not match.');
                window.location.href='admin_login.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Admin not found. Please check your username or password.');
            window.location.href='admin_login.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Gleam Creates</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <nav class="admin-navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="../index.php" class="navbar-brand">
                    <img src="../images/download.png" alt="gleam creates logo">
                </a>
                <div class="admin-badge">
                    <i class="fas fa-shield-alt"></i> Admin Portal
                </div>
            </div>
        </div>
    </nav>

    <!-- Form Content Layout Wrapper -->
    <div class="admin-login-wrapper">
        <div class="login-container">
            
            <div class="login-header">
                <i class="fas fa-user-shield"></i>
                <h2>Admin Login</h2>
                <p>Access your administrative dashboard</p>
            </div>

            <!-- Login Submission Form -->
            <form  method="post">
                
                <!-- Username Input Container -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-user"></i> Admin Username
                    </label>
                    <div class="input-group">
                        <input type="text" name="admin_username" class="form-control" placeholder="Enter your username" required>
                    </div>
                </div>

                <!-- Password Input Container -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="admin_password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>

                <!-- Login Trigger Button -->
                <button type="submit" name="admin_login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Log to Dashboard
                </button>

                <!-- Navigation Return Link -->
                <div class="back-link">
                    <a href="../index.php">
                        <i class="fas fa-arrow-left"></i> Back to Store
                    </a>
                </div>

            </form>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>

    <?php include('../includes/footer.php'); ?>

</body>
</html>