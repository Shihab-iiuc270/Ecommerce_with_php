<?php

include('../includes/connect.php'); // Adjust path based on your folder structure

if (!isset($_SESSION['username'])) {
    $_SESSION['toast_message'] = "Please login to update your profile";
    echo "<script>window.location.href='user_login.php';</script>";
    exit();
}

$username = $_SESSION['username'];
$query = "SELECT * FROM `user_table` WHERE username = ?";
$query = $con->prepare($query);
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$row_fetch = $result->fetch_assoc();

$user_id = $row_fetch['user_id'];
$user_username = $row_fetch['username'];
$user_email = $row_fetch['user_email'];
$user_address = $row_fetch['user_address'];
$user_mobile = $row_fetch['user_mobile'];
$user_image= $row_fetch['user_image'];

if (isset($_POST['user_update'])) {
    $new_username = $_POST['user_username'];
    $new_email    = $_POST['user_email'];
    $new_address  = $_POST['user_address'];
    $new_mobile   = $_POST['user_mobile'];
    
    $new_image    = $user_image; 
    
    $update_successful = false;
    $toast_message     = "Update failed. Please try again.";

    if (!empty($_FILES['user_image']['name'])) {
        $new_image      = $_FILES['user_image']['name'];
        $new_image_temp = $_FILES['user_image']['tmp_name'];
        
        if (!move_uploaded_file($new_image_temp, "./user_images/$new_image")) {
            $_SESSION['toast_message'] = "Failed to upload new image. Profile data not updated.";
            echo "<script>window.location.href='profile.php?edit_account';</script>";
            exit();
        }
    }

    $update_query = "UPDATE `user_table` SET username = ?, user_email = ?, user_image = ?, user_address = ?, user_mobile = ? WHERE user_id = ?";
    $update_stmt = $con->prepare($update_query);
    
    $update_stmt->bind_param("sssssi", $new_username, $new_email, $new_image, $new_address, $new_mobile, $user_id);
    $run_update = $update_stmt->execute();

    if ($run_update) {
        $_SESSION['username'] = $new_username;
        $_SESSION['toast_message']    = "Account updated successfully!";
        $update_successful    = true;
    }

    $update_stmt->close();

    $redirect_url = $update_successful ? "profile.php" : "profile.php?edit_account";
    
    echo "<script>window.location.href='$redirect_url';</script>";
    exit();
}
?>


<h3 class="text-custom-maroon mb-4">Update Account Details</h3>

<form action="" method="post" enctype="multipart/form-data" class="w-75">
    
    <!-- Username Field -->
    <div class="mb-3">
        <label for="user_username" class="form-label">Username</label>
        <input type="text" class="form-control" id="user_username" name="user_username" required value="<?php echo $user_username ?>">
    </div>

    <!-- Email Field -->
    <div class="mb-3">
        <label for="user_email" class="form-label">Email</label>
        <input type="email" class="form-control" id="user_email" name="user_email" required value="<?php echo $user_email ?>">
    </div>

    <!-- Profile Image Field -->
    <div class="mb-3">
        <label for="user_image" class="form-label">Profile Image</label>
        <div class="d-flex align-items-center">
            <input type="file" class="form-control" id="user_image" name="user_image">
            <!-- Dummy / Current Image Display -->
            <img src="user_images/<?php echo $user_image ?>" alt="current profile image" class="profile_image ms-3" style="width: 50px; height: 50px; object-fit: cover;">
        </div>
        <small class="form-text text-muted">Upload a new file to change your current profile picture</small>
    </div>

    <!-- Address Field -->
    <div class="mb-3">
        <label for="user_address" class="form-label">Address</label>
        <input type="text" class="form-control" id="user_address" name="user_address" required value="<?php echo $user_address ?>">
    </div>

    <!-- Mobile Number Field -->
    <div class="mb-3">
        <label for="user_mobile" class="form-label">Mobile Number(11 digits)</label>
        <input type="text" class="form-control" id="user_mobile" name="user_mobile" maxlength="11" required value="<?php echo $user_mobile ?>">
    </div>

    <!-- Submit Button -->
    <div class="d-grid gap-2">
        <input type="submit" class="btn btn-custom py-2" value="Update Profile" name="user_update">
    </div>

</form> 