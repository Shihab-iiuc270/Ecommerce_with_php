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

?>