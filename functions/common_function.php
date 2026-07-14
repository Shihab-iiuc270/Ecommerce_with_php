<?php
function get_occasion(){
    global $con;

    $select_occasions = "select * from `occasions`";
    $result_occasions = mysqli_query($con,$select_occasions);
    while($row = mysqli_fetch_assoc($result_occasions)){
    $occasion_id = $row['occasion_id'];
    $occasion_title = $row['occasion_title'];
    echo "<li class='nav-item'>
      <a href='index.php?occasion=$occasion_id' class='nav-link'>$occasion_title</a>
    </li>";
    }
}
function get_gift_categories(){
    global $con;

    $select_cetegories= "select * from `gift_categories`";
    $result_categories = mysqli_query($con,$select_cetegories);
    while($row = mysqli_fetch_assoc($result_categories)){
    $category_id = $row['category_id'];
    $category_title = $row['category_title'];
    echo "<li class='nav-item'>
      <a href='index.php?category=$category_id' class='nav-link'>$category_title</a>
    </li>";
    }
}
function getproducts(){
    global $con;
    if(!isset($_GET['category']) && !isset($_GET['occasion'])){
    $select_query= "select * from `products` ORDER BY RAND() LIMIT 0,9";
    $result_query = mysqli_query($con,$select_query);
    while($row = mysqli_fetch_assoc($result_query)){
    $product_id = $row['product_id'];
    $product_title = $row['product_title'];
    $product_description= $row['product_description'];
    $product_image1=$row['product_image1'];
    $product_price=$row['product_price'];
    echo "<div class='col-md-4 mb-4'>
          <div class='card'>
            <img src='images/$product_image1' alt='product image' class='card-img-top'>
            <div class='card-body'>
              <h5 class='card-title'>$product_title</h5>
              <p class='cart-text'>".substr($product_description,0,30)."...</p>
              <span class='card-price'>$product_price/-</span>
              <div class='card-actions d-flex gap-2 mt-2'>
                <a href='index.php?add_to_cart=$product_id' class='btn btn-custom'>
                  <i class='fas fa-cart-plus me-2'></i>
                </a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'>
                  <i class='fas fa-eye me-2'></i>View
                </a>
              </div>
            </div>
          </div>
        </div>";
    }
    }
    
}
function get_categories_unique(){
    global $con;

    if(isset($_GET['category'])){

        $category_id = intval($_GET['category']);
        $select_query = "select * from `products` where category_id=$category_id";
        $result_query = mysqli_query($con,$select_query);
        $num_of_rows = mysqli_num_rows($result_query);
        if($num_of_rows==0){
            echo "<div class='col-12'><h2 class='text-center text-danger'>No Products in this Category</h2></div>";
        }
    while($row = mysqli_fetch_assoc($result_query)){
    $product_id = $row['product_id'];
    $product_title = $row['product_title'];
    $product_description= $row['product_description'];
    $product_image1=$row['product_image1'];
    $product_price=$row['product_price'];
    echo "<div class='col-md-4 mb-4'>
          <div class='card'>
            <img src='images/$product_image1' alt='product image' class='card-img-top'>
            <div class='card-body'>
              <h5 class='card-title'>$product_title</h5>
              <p class='cart-text'>".substr($product_description,0,30)."...</p>
              <span class='card-price'>$product_price/-</span>
              <div class='card-actions d-flex gap-2 mt-2'>
                <a href='index.php?add_to_cart=$product_id' class='btn btn-custom'>
                  <i class='fas fa-cart-plus me-2'></i>
                </a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'>
                  <i class='fas fa-eye me-2'></i>View
                </a>
              </div>
            </div>
          </div>
        </div>";


    }
}
}
function get_occasions_unique(){
    global $con;

    if(isset($_GET['occasion'])){

        $occasion_id = intval($_GET['occasion']);
        $select_query = "select * from `products` where occasion_id=$occasion_id";
        $result_query = mysqli_query($con,$select_query);
        $num_of_rows = mysqli_num_rows($result_query);
        if($num_of_rows==0){
            echo "<div class='col-12'><h2 class='text-center text-danger'>No Products in this Occasion</h2></div>";
        }
    while($row = mysqli_fetch_assoc($result_query)){
    $product_id = $row['product_id'];
    $product_title = $row['product_title'];
    $product_description= $row['product_description'];
    $product_image1=$row['product_image1'];
    $product_price=$row['product_price'];
    echo "<div class='col-md-4 mb-4'>
          <div class='card'>
            <img src='images/$product_image1' alt='product image' class='card-img-top'>
            <div class='card-body'>
              <h5 class='card-title'>$product_title</h5>
              <p class='cart-text'>".substr($product_description,0,30)."...</p>
              <span class='card-price'>$product_price/-</span>
              <div class='card-actions d-flex gap-2 mt-2'>
                <a href='index.php?add_to_cart=$product_id' class='btn btn-custom'>
                  <i class='fas fa-cart-plus me-2'></i>
                </a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'>
                  <i class='fas fa-eye me-2'></i>View
                </a>
              </div>
            </div>
          </div>
        </div>";


    }
}
}

   function get_all_products(){
    global $con;
    if(!isset($_GET['category']) && !isset($_GET['occasion'])){
    $select_query= "select * from `products` ORDER BY RAND() LIMIT 0,9";
    $result_query = mysqli_query($con,$select_query);
    while($row = mysqli_fetch_assoc($result_query)){
    $product_id = $row['product_id'];
    $product_title = $row['product_title'];
    $product_description= $row['product_description'];
    $product_image1=$row['product_image1'];
    $product_price=$row['product_price'];
    echo "<div class='col-md-4 mb-4'>
          <div class='card'>
            <img src='images/$product_image1' alt='product image' class='card-img-top'>
            <div class='card-body'>
              <h5 class='card-title'>$product_title</h5>
              <p class='cart-text'>".substr($product_description,0,30)."...</p>
              <span class='card-price'>$product_price/-</span>
              <div class='card-actions d-flex gap-2 mt-2'>
                <a href='index.php?add_to_cart=$product_id' class='btn btn-custom'>
                  <i class='fas fa-cart-plus me-2'></i>
                </a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'>
                  <i class='fas fa-eye me-2'></i>View
                </a>
              </div>
            </div>
          </div>
        </div>";
    }
    }
    
}
    
function view_details(){
    global $con;
    if(isset($_GET['product_id']) && filter_var($_GET['product_id'], FILTER_VALIDATE_INT)){
        $product_id = (int)$_GET['product_id'];
        $select_product_query = "select * from `products` where product_id = ?";
        $stmt = mysqli_prepare($con, $select_product_query);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result) == 0){
            echo "<h3 class='text-center text-danger my-5'>Product not found or is unavailable.</h3>";
        } else {
            while($row = mysqli_fetch_assoc($result)){
                $product_id = $row['product_id'];
                $product_title = htmlspecialchars($row['product_title']);
                $product_description = htmlspecialchars($row['product_description']);
                $product_image1 = htmlspecialchars($row['product_image1']);
                $product_image2 = htmlspecialchars($row['product_image2']);
                $product_image3 = htmlspecialchars($row['product_image3']);
                $product_price = htmlspecialchars($row['product_price']);

                $base_image_path = 'images/';
                
                echo "
                <div class='col-md-6 text-center'>
                    <img src='{$base_image_path}{$product_image1}' alt='{$product_title}' class='product-main-image mb-4 img-fluid'>
                    <div class='d-flex justify-content-center gap-3'>
                        <img src='{$base_image_path}{$product_image1}' alt='thumbnail 1' class='product-small-image' style='width: 100px; cursor: pointer;' onclick=\"changeImage('{$base_image_path}{$product_image1}')\">";

                if(!empty($product_image2)){
                    echo "<img src='{$base_image_path}{$product_image2}' alt='thumbnail 2' class='product-small-image' style='width: 100px; cursor: pointer;' onclick=\"changeImage('{$base_image_path}{$product_image2}')\">";
                }
                if(!empty($product_image3)){
                    echo "<img src='{$base_image_path}{$product_image3}' alt='thumbnail 3' class='product-small-image' style='width: 100px; cursor: pointer;' onclick=\"changeImage('{$base_image_path}{$product_image3}')\">";
                }

                echo "
                    </div>
                </div>
                <div class='col-md-6'>
                    <h2 class='product-title'>{$product_title}</h2>
                    <h4 class='product-price'>\${$product_price}</h4>
                    <p class='product-description'>{$product_description}</p>
                    <a href='index.php?add_to_cart={$product_id}' class='btn btn-custom mt-3'>
                        <i class='fa-solid fa-cart-shopping text-light'></i> Add To Cart
                    </a>
                    <a href='index.php' class='btn btn-view-product mt-3'>
                        <i class='fa-solid fa-house'></i> Go Home
                    </a>
                </div>";
            }
        } // End of else block
        
        mysqli_stmt_close($stmt);

    }
    else{
      echo "<h3 class='text-center text-danger my-5'>Invalid Product Selected or Product Id is missing </h3>";
    }
}

function search_products(){
  global $con;
  if(isset($_GET['search_data_product'])){
       $search_data_value = mysqli_real_escape_string($con,$_GET['search_data']);
       $select_query = "select * from `products` where product_keyword LIKE '%$search_data_value%' or product_title LIKE '%$search_data_value%'";
       $result_query=mysqli_query($con,$select_query);
     if(mysqli_num_rows($result_query) == 0){
            echo "<h3 class='text-center text-danger my-5'>Product not found matching \"$search_data_value\".</h3>";
     }else{
       while($row = mysqli_fetch_assoc($result_query)){
    $product_id = $row['product_id'];
    $product_title = $row['product_title'];
    $product_description= $row['product_description'];
    $product_image1=$row['product_image1'];
    $product_price=$row['product_price'];
    echo "<div class='col-md-4 mb-4'>
          <div class='card'>
            <img src='images/$product_image1' alt='product image' class='card-img-top'>
            <div class='card-body'>
              <h5 class='card-title'>$product_title</h5>
              <p class='cart-text'>".substr($product_description,0,30)."...</p>
              <span class='card-price'>$product_price/-</span>
              <div class='card-actions d-flex gap-2 mt-2'>
                <a href='index.php?add_to_cart=$product_id' class='btn btn-custom'>
                  <i class='fas fa-cart-plus me-2'></i>
                </a>
                <a href='product_details.php?product_id=$product_id' class='btn btn-view-product'>
                  <i class='fas fa-eye me-2'></i>View
                </a>
              </div>
            </div>
          </div>
        </div>";
    }
    }

  }
}
function cart(){
  global $con;
  if(isset($_GET['add_to_cart'])){
    $get_product_id= intval($_GET['add_to_cart']);
    if(isset($_SESSION['username'])){
      $username = $_SESSION['username'];
      $stmt= $con->prepare("select user_id from `user_table`
      where username=?");
      $stmt->bind_param('s',$username);
      $stmt->execute();
      $result=$stmt->get_result();
      $user_data=$result->fetch_assoc();
      $user_id = $user_data['user_id'];
      
      $check_cart=$con->prepare("select * from `cart_details` 
      where user_id=? AND product_id=?");
      $check_cart->bind_param("ii",$user_id,$get_product_id);
      $check_cart->execute();
      $result_check=$check_cart->get_result();
      if($result_check->num_rows>0){
        $update=$con->prepare("update `cart_details` set quantity=quantity+1
        where user_id=? AND product_id=?");
        $update->bind_param("ii",$user_id,$get_product_id);
      $update->execute();
      $_SESSION['toast_message']="Product quantity updated to cart";


      }
      else{
      $insert=$con->prepare("insert into `cart_details` (user_id,product_id,quantity)
      values (?,?,1)");
      $insert->bind_param("ii",$user_id,$get_product_id);
      $insert->execute();
      $_SESSION['toast_message']="Product added to cart successfully";

    }

    }else{
      if(!isset($_SESSION['cart']))
        $_SESSION['cart']=[];

        if(isset($_SESSION['cart'][$get_product_id])){
          $_SESSION['cart'][$get_product_id]+=1;
          $_SESSION['toast_message']="Product quantitu updated in cart";

        }else{
        $_SESSION['cart'][$get_product_id]=1;
          $_SESSION['toast_message']="Product added to cart successfully";


        };
      
        
        }              
        $redirect_uri=strtok($_SERVER['REQUEST_URI'],'?');
        echo "<script>window.location.href='$redirect_uri';</script>";
        exit();
        }
  }
function cart_item(){
  global $con;
  $count = 0;
  
  // 🌟 FIXED TYPO: "username" instead of "usernamme"
  if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    
    $stmt = $con->prepare("select user_id from user_table where username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // 🌟 ADDED SAFETY CHECK: Ensure user data was actually found in the database
    if($result->num_rows > 0) {
      $user_data = $result->fetch_assoc();
      $user_id = $user_data['user_id'];
      
      $count_query = $con->prepare("select sum(quantity) as total from cart_details where user_id =?");
      $count_query->bind_param("i", $user_id);
      $count_query->execute();
      $data = $count_query->get_result()->fetch_assoc();
      
      // If the cart is empty, sum(quantity) returns NULL. Fallback to 0.
      $count = $data['total'] ?? 0;
    }
  } else {
    if(isset($_SESSION['cart'])){
      $count = array_sum($_SESSION['cart']);
    }
  }
  
  echo $count;
}

function total_price() {
    global $con;
    $total = 0;

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        
        if (!isset($_SESSION['user_id'])) {
            $stmt = $con->prepare("SELECT user_id FROM user_table WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['user_id'];
        }
        
        $user_id = $_SESSION['user_id'];
        
        $sql = "SELECT p.product_price, c.quantity 
                FROM cart_details c 
                JOIN products p ON c.product_id = p.product_id 
                WHERE c.user_id = ?";
                
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $items = $stmt->get_result();
        while ($row = $items->fetch_assoc()) {
            $total += $row['product_price'] * $row['quantity'];
        }

    } else {
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $product_ids = array_keys($_SESSION['cart']);
                $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
            
            $sql = "SELECT product_id, product_price FROM products WHERE product_id IN ($placeholders)";
            $stmt = $con->prepare($sql);
            
            $types = str_repeat("i", count($product_ids));
            
           $stmt->bind_param($types, ...$product_ids);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $product_id = $row['product_id'];
                $total += $row['product_price'] * $_SESSION['cart'][$product_id];
            }
        }
    }
    
   echo $total;
}

?>
