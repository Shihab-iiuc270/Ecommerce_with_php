<?php
session_start();
include("../includes/connect.php");


if(isset($_SESSION['username'])){
    echo "<script>window.location.href='./profile.php'</script>";
    exit();
}

if(isset($_POST['user_register'])){
    $user_username= trim($_POST['user_username']);
    $user_email= trim($_POST['user_email']);
    $user_password= $_POST['user_password'];
    $conf_user_password= $_POST['conf_user_password'];
    $user_address= trim($_POST['user_address']);
    $user_contact= trim($_POST['user_contact']);
    $user_image= $_FILES['user_image']['name'];
    $user_image_tmp= $_FILES['user_image']['tmp_name'];
    $toast_message="";
    $registration_success=false;
    if(empty($user_username) || empty($user_email) || empty($user_password) || empty($user_address) || empty($user_contact) || empty($user_image)){
        $toast_message= "Please fill all the required fields";
    }elseif(!preg_match("/^\d{11}$/",$user_contact)){
        echo "Contact number must be 11 digits";
    }else{
        $check_query = "select * from `user_table` where username=? or user_email=?";
        $stmt_check = $con->prepare($check_query);
        $stmt_check->bind_param("ss",$user_username,$user_email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        if($result->num_rows>0){
            $toast_message="Username or Email already Exists";
        }elseif($user_password!==$conf_user_password){
    $toast_message= "Passwords do not match";
        }
        else{
  $hashed_password = password_hash($user_password,PASSWORD_DEFAULT);


    $image_upload_path="./user_images/$user_image";
    if(move_uploaded_file($user_image_tmp,$image_upload_path)){

    $insert_query = "insert into `user_table` (username,user_email,user_password,user_image,user_address,user_mobile) values (?,?,?,?,?,?)";
   $stmt_insert= $con->prepare($insert_query);
   $stmt_insert->bind_param('ssssss',$user_username,$user_email,$hashed_password,
   $user_image,$user_address,$user_contact);
   $result_insert=$stmt_insert->execute();
   $_SESSION['username']=$user_username;
    $toast_message="Registration Successful";
    $registration_success=true;
$_SESSION['toast_message']=$toast_message;
if($registration_success){
    echo "<script>window.location.href='../index.php'</script>";
    exit();
}else{
    header("Location: user_registration.php");
    exit();
}

    }
        }
    }

    

}
$page_title="E-commerce User Registration";
include('../includes/header.php');
include("../includes/navbar.php");

?>

<div class="container my-5">
    <div class="card p-4 mx-auto registration-card">
        <h2 class="text-center mb-4 text-custom-maroon">Create New Account</h2>
        <form action="" enctype="multipart/form-data" method="post">
            <div class="mb-3">
                <label for="user_username" class="form-label">Username</label>
                <input type="text" name="user_username"
                class="form-control"  id="user_username" placeholder="Enter username" required>
            </div>
            <div class="mb-3">
                <label for="user_email" class="form-label">Email</label>
                <input type="email" name="user_email"
                class="form-control"  id="user_email" placeholder="Enter Email" required>
            </div>
            <div class="mb-3">
                <label for="user_image" class="form-label">Profile Image</label>
                <input type="file" name="user_image"
                class="form-control"  id="user_image" placeholder="Enter image" required>
            </div>
            <div class="mb-3">
                <label for="user_password" class="form-label">Password</label>
                <input type="password" name="user_password"
                class="form-control"  id="user_password" placeholder="Create a password" required>
            </div>
            <div class="mb-3">
                <label for="conf_user_password" class="form-label">Confirm Password</label>
                <input type="password" name="conf_user_password"
                class="form-control"  id="conf_user_password" placeholder="confirm password" required>
            </div>
            <div class="mb-3">
                <label for="user_address" class="form-label">Address</label>
                <input type="text" name="user_address"
                class="form-control"  id="user_address" placeholder="Address" required>
            </div>
            <div class="mb-3">
                <label for="user_contact" class="form-label">Mobile</label>
                <input type="text" name="user_contact"
                class="form-control"  id="user_contact" placeholder="11-digit Mobile Number" required>
            </div>
            <div class="d-grid">
                <input type="submit" name="user_register" class="btn btn-custom py-2" value="Register">
            </div>
            <p class="text-center mt-3">Already have an account? <a href="user_login.php" class="text-custom-maroon">LogIn</a></p>
        </form>
    </div>
</div>

<?php include("../includes/footer.php") ?>
<?php include("../includes/scripts_footer.php")?>
