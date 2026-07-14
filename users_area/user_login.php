<?php
session_start();
include("../includes/connect.php");
if(isset($_SESSION['username'])){
    header("Location: profile.php");
    exit();

}

//login form submission

if(isset($_POST['user_login'])){
    $username=trim($_POST['username']);
    $user_password=$_POST['user_password'];

    $select_query = "select * from `user_table` where username=? ";
    $stmt= $con->prepare($select_query);
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result=$stmt->get_result();

    $row_count=$result->num_rows;
    if($row_count>0){
        $row_data = $result->fetch_assoc();
        if(password_verify($user_password,$row_data['user_password'])){
            
            $_SESSION['username']=$row_data['username'];
            $_SESSION['user_id']=$row_data['user_id'];
            $_SESSION['toast_message']="welcome".htmlspecialchars($username)."Login Successful";
          header("Location: profile.php");
    exit();
        }
        else{
    $_SESSION['toast_message']="Invalid password . Pls try agains";

        }
    }else{
            $_SESSION['toast_message']="User not found";

    }

}


$page_title="E-User LogIn";
include('../includes/header.php');
include("../includes/navbar.php");
// include("./functions/common_function.php");
?>

<div class="container my-5">
<h2 class="text-center mb-4 text-custom-maroon">User LogIn</h2>
<form action="" method="post" class="mx-auto" style="max-width: 400px;">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required class="form-control">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="user_password" name="user_password" placeholder="Enter your password" required class="form-control">
    </div>
    <button type="submit"  name="user_login" class="btn btn-custom w-100 mb-3">LogIn</button>
    <p class="text-center">Don't have an account?
        <a href="user_registration.php" class="text-custom-maroon fw-bold">Registration Here</a>
    </p>
</form>
</div>

<?php include('../includes/footer.php'); ?>
<?php include("../includes/scripts_footer.php")?>
