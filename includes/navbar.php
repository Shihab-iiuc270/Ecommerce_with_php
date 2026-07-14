<?php
$project_folder='ecommerce';

include_once("connect.php");

//$_SERVER['DOCUMENT_ROOT']=C:/xampp/htdocs/
$common_function_path = $_SERVER['DOCUMENT_ROOT'].'/'.
$project_folder.'/functions/common_function.php';
if(file_exists($common_function_path)){
  include_once($common_function_path);
}else{
  error_log("CRITICAL ERROR: common_functions.php not found at ".$common_function_path);
}
if(!isset($base_url)){
$base_url="/ecommerce/";
}
function get_absolute_link($path,$base_url){
  $clean_path=ltrim($path,'./');
  return rtrim($base_url,'/').'/'.$clean_path;
}
?>



<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top px-4">
  <div class="container-fluid">
    <a class="navbar-brand logo-link" href="<?= get_absolute_link('index.php',$base_url) ?>">
        <img src="<?= get_absolute_link('images/download.png',$base_url) ?>" alt="E-commerce Logo" class="d-inline-block align-text-top gleam-logo">
    </a>
    <!-- togglebutton -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
<!-- navItems -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link " href="<?php echo get_absolute_link('index.php',$base_url)?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= get_absolute_link('display_all.php',$base_url) ?>">Products</a>
        </li>
        <?php
        if(isset($_SESSION['username'])){
      echo "<li class='nav-item'>
        <a class='nav-link' href='" . get_absolute_link('users_area/profile.php', $base_url) . "'>My Account</a>
      </li>";
        }else{
      echo "<li class='nav-item'>
        <a class='nav-link' href='" . get_absolute_link('users_area/user_registration.php', $base_url) . "'>Register</a>
      </li>";
        }
        ?>
        <?php
        if(!isset($_SESSION['username'])){
        echo "<li class='nav-item'>
        <a class='nav-link' href='" . get_absolute_link('users_area/user_login.php', $base_url) . "'>LogIn</a>
      </li>";
        }else{
        echo "<li class='nav-item'>
        <a class='nav-link' href='" . get_absolute_link('users_area/logout.php', $base_url) . "'>LogOut</a>
      </li>";
        }
        ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo get_absolute_link('cart.php',$base_url)?>">
<i class="fa-notdog-duo fa-solid fa-cart-shopping"></i>
<sup><?php echo cart_item();?></sup>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="#">Total : $<?php total_price()?> </a>
        </li>
      </ul>
      <form class="d-flex" role="search" action="<?php echo get_absolute_link('search_product.php',$base_url)?>" method="get">
        <input class="form-control me-2" type="search" placeholder="Search products..." style="min-width :200px;;" name="search_data"/>
        <button class="btn btn-custom" type="submit" name="search_data_product">
          <i class="fas fa-search"></i>
        </button>
      </form>
    </div>
  </div>
</nav>