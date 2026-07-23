<?php
include('../includes/connect.php');
if(isset($_POST['insert_product'])){
    $product_title=$_POST['product_title'];
    $product_description=$_POST['description'];
    $product_price=filter_var($_POST['product_price'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    $product_keywords   = $_POST['product_keywords'];
    $product_category = $_POST['product_category'];
    $product_occasion = $_POST['product_occasion'];
    $status = "true";

    $product_image1 = $_FILES['product_image1']['name'];
    $temp_image1    = $_FILES['product_image1']['tmp_name'];


    if (isset($_FILES['product_image2']['name']) && $_FILES['product_image2']['error'] == 0) {
        $product_image2 = $_FILES['product_image2']['name'];
        $temp_image2    = $_FILES['product_image2']['tmp_name'];
    } else {
        $product_image2 = "";
        $temp_image2    = "";
    }
   
if (isset($_FILES['product_image3']['name']) && $_FILES['product_image3']['error'] == 0) {
        $product_image3 = $_FILES['product_image3']['name'];
        $temp_image3    = $_FILES['product_image3']['tmp_name'];
    } else {
        $product_image3 = "";
        $temp_image3    = "";
    }
    if (empty($product_title) ||    empty($product_description) || 
        empty($product_keywords) ||   empty($product_price) || 
        empty($product_category) ||  empty($product_occasion) || 
        empty($product_image1)
    ) {
        echo "<script>alert('Please fill all the required fields');</script>";
    } else {

       move_uploaded_file($temp_image1, "./product_images/$product_image1");

        if (!empty($product_image2)) {
            move_uploaded_file($temp_image2, "./product_images/$product_image2");
        }

        if (!empty($product_image3)) {
            move_uploaded_file($temp_image3, "./product_images/$product_image3");
        }
        $insert_products = "INSERT INTO `products` 
            (product_title, product_description, product_keyword, category_id, occasion_id, product_image1, product_image2, product_image3, product_price, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $con->prepare($insert_products);
        $stmt->bind_param(
            "sssiisssds",
            $product_title, $product_description, $product_keywords,
            $product_category, $product_occasion,$product_image1,
            $product_image2,  $product_image3, $product_price,
            $status
        );

        $result = $stmt->execute();

        if ($result) {
            echo "<script>alert('$product_title inserted successfully');</script>";
            echo "<script>window.location.href='index.php?view_products';</script>";
        } else {
            echo "<script>alert('Error inserting product');</script>";
        }
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-4">
        <!-- Text color uses your CSS secondary-color variable -->
        <i class="fas fa-plus-circle" style="color: var(--secondary-color);"></i> Insert New Product
    </h2>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- enctype is REQUIRED for handling file/image uploads -->
                    <form method="post" enctype="multipart/form-data">
                        
                        <!-- Product Title -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag"></i> Product Title</label>
                            <input type="text" name="product_title" class="form-control" placeholder="eg. Birthday gift Box" required>
                        </div>

                        <!-- Product Description -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-align-left"></i> Product Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Enter detailed product description..." required>
                        </div>

                        <!-- Product Keywords -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-key"></i> Product Keywords</label>
                            <input type="text" name="product_keywords" class="form-control" placeholder="eg: Birthday gift, celebration (comma separated)" required>
                        </div>

                        <!-- Category & Occasion Grid Row -->
                        <div class="row">
                            <!-- Category Dropdown -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-folder"></i> Category</label>
                                <select name="product_category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <?php  $stmt_cat =$con->prepare("select category_id,category_title
                                    from gift_categories  ORDER BY category_title");
                
                                    $stmt_cat->execute();
                                    $result_cat=$stmt_cat->get_result();
                                    while($row=$result_cat->fetch_assoc()){
                                        echo "<option value='{$row['category_id']}'>{$row['category_title']}</option>";
                                    }  ?>
                                    <!-- Dynamic categories will be fetched here via PHP later -->
                                </select>
                            </div>
                            
                            <!-- Occasion Dropdown -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fas fa-calendar-alt"></i> Occasion</label>
                                <select name="product_occasion" class="form-select" required>
                                    <option value="">Select Occasion</option>
                                    <?php  $stmt_occasion =$con->prepare("select occasion_id,occasion_title
                                    from occasions ORDER BY occasion_title");
                                    $stmt_occasion->execute();
                                    $result_occasion=$stmt_occasion->get_result();
                                    while($row=$result_occasion->fetch_assoc()){
                                        echo "<option value='{$row['occasion_id']}'>{$row['occasion_title']}</option>";
                                    }  ?>
                                    <!-- Dynamic occasions will be fetched here via PHP later -->
                                </select>
                            </div>
                        </div>

                        <!-- Product Price -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-indian-rupee-sign"></i> Product Price</label>
                            <input type="number" name="product_price" class="form-control" placeholder="Enter product price" min="1" required>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="fas fa-images"></i> Product Images</h5>

                        <!-- Primary Image (Required) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image"></i>Primary Image (Required)</label>
                            <input type="file" name="product_image1" class="form-control" accept="image/*" required>
                            <small class="text-muted">This will be the main display image.</small>
                        </div>

                        <!-- Additional Image 2 (Optional) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Additional Image 2 (Optional)</label>
                            <input type="file" name="product_image2" class="form-control" accept="image/*">
                        </div>

                        <!-- Additional Image 3 (Optional) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Additional Image 3 (Optional)</label>
                            <input type="file" name="product_image3" class="form-control" accept="image/*">
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <button type="submit" name="insert_product" class="btn btn-primary px-5">
                                <i class="fas fa-circle-check"></i> Insert Product
                            </button>
                            <a href="index.php?view_products" class="btn btn-secondary px-4 ms-2">
                                <i class="fas fa-times-circle"></i> Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>