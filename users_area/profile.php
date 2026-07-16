<?php
session_start();
include("../includes/connect.php");
include("../functions/common_function.php");

if(!isset($_SESSION['username'])){
    $_SESSION['toast_message']="please login to view your profile";
    echo "<script>window.location.href='user_login.php'</script>";
}
$username=$_SESSION['username'];
$query = $con->prepare("SELECT * FROM user_table WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();

$result = $query->get_result();
$row_count = $result->num_rows;

if ($row_count > 0) {
    $user = $result->fetch_assoc();
} else {
    // Fallback default values if network errors happen or user row is missing
    $user = [
        'username' => $username,
        'user_email' => 'not available',
        'user_mobile' => 'not available',
        'user_address' => 'not available',
        'user_image' => 'default_image.jpg'
    ];
}

$user_id = isset($user['user_id']) ? $user['user_id'] : null;
$current_view='profile';
if(isset($_GET['orders'])){
    $current_view='orders';
}elseif(isset($_GET['edit_account'])){
        $current_view='edit_account';
}elseif(isset($_GET['delete_account'])){ 
    $current_view='delete_account';
}
$page_title = ucfirst($username) . " - My Account";
include("../includes/header.php");
include("../includes/navbar.php");


?>
<div class="container-fluid my-5">
    <div class="row mx-auto">
        <!-- Left Section -->
        <div class="col-md-3">
            <div class="card p-3 profile-sidebar">
                <h4 class="text-custom-maroon text-center mb-4">Dashboard</h4>
                <div class="text-center mb-4">
                    <img src="./user_images/<?php echo $user['user_image']?>" alt="<?php echo $user['username']?>" class="rounded-circle profile-img" onerror="this.src='../images/download.png'">
                    <h5 class="mt-3"><?php echo $user['username']?></h5>
                </div>
                <h4 class="text-custom-maroon text-center mb-4">Dashboard</h4>
                <ul class="nav flex-column nav-pills">
                    <li class="nav-item mb-2">
                        <a href="profile.php" class="nav-link link-custom <?php echo ($current_view=='profile'?'active bg-custom-maroon text-light':"") ?> ">
                            <i class="fas fa-user-circle me-2"></i>My Profile
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="profile.php?orders" class="nav-link link-custom <?php echo ($current_view=='orders'?'active bg-custom-maroon text-light':"") ?> ">
                            <i class="fas fa-box me-2"></i>My Orders
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="profile.php?edit_account" class="nav-link link-custom <?php echo ($current_view=='edit_account'?'active bg-custom-maroon text-light':"") ?> ">
                            <i class="fas fa-edit me-2"></i> Edit Profile
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="profile.php?delete_account" class="nav-link link-custom <?php echo ($current_view=='delete_account'?'active bg-custom-maroon text-light':"") ?> ">
                            <i class="fas fa-trash me-2"></i> Delete Account
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="logout.php" class="nav-link link-custom">
                            <i class="fas fa-sign-out-alt me-2"></i> Log Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-md-9">
            <div class="p-4 profile-content">
                <?php 
                if($current_view=='edit_account'){
                    include("edit_account.php");
                }elseif($current_view=='orders'){
                    include("user_orders.php");
                }elseif($current_view=='delete_account'){
                     include("delete_account.php");
                }else{
               echo "
                <h3 class='text-custom-maroon mb-4'>Welcome to dashboard,".htmlspecialchars($user['username'])."</h3>
                <p class='lead'>Here you can manage your account information and track your orders.</p>
                
                <div class='card mt-4 border-light shadow-sm'>
                    <h5 class='p-3 mb-0'>Account Details</h5>
                    <table class='table table-borderless mt-3'>
                        <tr>
                            <th>Username:</th>
                            <td>".$user['username']."</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>".$user['user_email']."</td>
                        </tr>
                        <tr>
                            <th>Mobile:</th>
                            <td>".$user['user_mobile']."</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>".$user['user_address']."</td>
                        </tr>
                    </table>
                    <a href='profile.php?edit_account' class='btn btn-custom w-50 my-3'>Update Profile</a>
                    </div>
               ";
               };
        
                    ?>
            </div>
        </div>
    </div>
</div>
<hr class="mt-5 mb-0">
<?php include("../includes/footer.php");
include("../includes/scripts_footer.php");
?>