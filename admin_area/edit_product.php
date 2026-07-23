<?php
include('../includes/connect.php');

if (isset($_GET['edit_products'])) {
    $edit_id = $_GET['edit_products'];

    $stmt = $con->prepare("SELECT * FROM `products` WHERE product_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    $product_title = $row['product_title'];
    $product_description = $row['product_description'];
    $product_keywords = $row['product_keyword'];
    $category_id = $row['category_id'];
    $occasion_id = $row['occasion_id'];
    $product_image1 = $row['product_image1'];
    $product_image2 = $row['product_image2'];
    $product_image3 = $row['product_image3'];
    $product_price = $row['product_price'];

    $stmt_cat = $con->prepare("SELECT * FROM `gift_categories` WHERE category_id = ?");
    $stmt_cat->bind_param("i", $category_id);
    $stmt_cat->execute();
    $result_category = $stmt_cat->get_result();
    $row_category = $result_category->fetch_assoc();
    $current_category_title = $row_category['category_title'];

    $stmt_occ = $con->prepare("SELECT * FROM `occasions` WHERE occasion_id = ?");
    $stmt_occ->bind_param("i", $occasion_id);
    $stmt_occ->execute();
    $result_occasion = $stmt_occ->get_result();
    $row_occasion = $result_occasion->fetch_assoc();
    $current_occasion_title = $row_occasion['occasion_title'];
}
?>


<div class="container mt-4">
    <h2 class="mb-4">
        <!-- Text color uses your CSS secondary-color variable -->
        <i class="fas fa-edit" style="color: var(--secondary-color);"></i> Edit Product
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
                            <input type="text" name="product_title" class="form-control"
                             placeholder="eg. Birthday gift Box" required value="<?php echo htmlspecialchars($product_title); ?>">
                        </div>

                        <!-- Product Description -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-align-left"></i> Product Description</label>
                            <input type="text" name="product_description" class="form-control"
                             placeholder="Enter detailed product description..." 
                             required value="<?php echo htmlspecialchars($product_description); ?>">
                        </div>

                        <!-- Product Keywords -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-key"></i> Product Keywords</label>
                            <input type="text" name="product_keywords" class="form-control"
                             placeholder="eg: Birthday gift, celebration (comma separated)" required value="<?php echo htmlspecialchars($product_keywords); ?>">
                        </div>

                        <!-- Category & Occasion Grid Row -->
                        <div class="row">
                            <!-- Category Dropdown -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-folder"></i> Category</label>
                                <select name="product_category" class="form-select" required>
                                    <option value="<?php echo ($category_id); ?>"><?php echo htmlspecialchars($current_category_title); ?></option>
                                     <?php  $stmt_cat =$con->prepare("select category_id,category_title
                                    from gift_categories WHERE category_id!=?  ORDER BY category_title");
                                    $stmt_cat->bind_param('i',$category_id);
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
                                    <option value="<?php echo htmlspecialchars($occasion_id); ?>"><?php echo htmlspecialchars($current_occasion_title); ?> </option>
                                    <?php  $stmt_occasion =$con->prepare("select occasion_id,occasion_title
                                    from occasions WHERE occasion_id!=?  ORDER BY occasion_title");
                                   $stmt_occasion->bind_param('i',$occasion_id);
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
                            <input type="number" name="product_price" class="form-control" placeholder="Enter product price" min="1"
                             required value="<?php echo htmlspecialchars($product_price); ?>">
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="fas fa-images"></i> Product Images</h5>
                        <p class="text-muted mb-3">Leave Empty</p>
                        <!-- Primary Image (Required) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image"></i>Primary Image
                            </label>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                            <input type="file" name="product_image1" class="form-control"  accept="image/">        
                                </div>
                                <div class="col-md-4">
                                    <div class="image-preview">
                                        <img src="./product_images/<?php echo $product_image1; ?>" alt="Preview Image">
                                    </div>
                                    <small class="text-muted d-blick text-center mt-1">Current Image</small>

                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image"></i> Image 1
                            </label>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                            <input type="file" name="product_image2" class="form-control"  accept="image/">        
                                </div>
                                <div class="col-md-4">
                                    <div class="image-preview">
                                        <img src="./product_images/<?php echo ($product_image2); ?>" alt="Preview Image">
                                    </div>
                                    <small class="text-muted d-blick text-center mt-1">Current Image</small>

                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image"></i>Image 2
                            </label>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                            <input type="file" name="product_image3" class="form-control"  accept="image/">        
                                </div>
                                <div class="col-md-4">
                                    <div class="image-preview">
                                        <img src="./product_images/<?php echo ($product_image3); ?>" alt="Preview Image">
                                    </div>
                                    <small class="text-muted d-blick text-center mt-1">Current Image</small>

                                </div>
                            </div>
                        </div>

                        <!-- Additional Image 2 (Optional) -->
                      

                        <!-- Additional Image 3 (Optional) -->

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <button type="submit" name="edit_products" class="btn btn-primary px-5">
                                <i class="fas fa-circle-check"></i> Update Product
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

<?php
if (isset($_POST['edit_products'])) {
    $product_title = $_POST['product_title'];
    $product_description = $_POST['product_description'];
    $product_keywords = $_POST['product_keywords'];
    $category_id = $_POST['product_category'];
    $occasion_id = $_POST['product_occasion'];
    $product_price = $_POST['product_price'];

    $product_image1_new = $_FILES['product_image1']['name'];
    $product_image2_new = $_FILES['product_image2']['name'];
    $product_image3_new = $_FILES['product_image3']['name'];

    $temp1 = $_FILES['product_image1']['tmp_name'];
    $temp2 = $_FILES['product_image2']['tmp_name'];
    $temp3 = $_FILES['product_image3']['tmp_name'];

    if (empty($product_image1_new)){
        $product_image1_final = $product_image1;
    } else {
        $product_image1_final = $product_image1_new;
        move_uploaded_file($temp1, "./product_images/$product_image1_final");
    }

    if (empty($product_image2_new)) {
        $product_image2_final = $product_image2; // Keep existing image from DB
    } else {
        $product_image2_final = $product_image2_new;
        move_uploaded_file($temp2, "./product_images/$product_image2_final");
    }

    if (empty($product_image3_new)) {
        $product_image3_final = $product_image3; // Keep existing image from DB
    } else {
        $product_image3_final = $product_image3_new;
        move_uploaded_file($temp3, "./product_images/$product_image3_final");
    }

    $update = $con->prepare("UPDATE `products` SET 
        product_title = ?, 
        product_description = ?, 
        product_keyword = ?, 
        occasion_id = ?, 
        category_id = ?, 
        product_image1 = ?, 
        product_image2 = ?, 
        product_image3 = ?, 
        product_price = ? 
        WHERE product_id = ?");

    $update->bind_param(
        "sssiisssdi", 
        $product_title, 
        $product_description, 
        $product_keywords, 
        $occasion_id, 
        $category_id, 
        $product_image1_final, 
        $product_image2_final, 
        $product_image3_final, 
        $product_price, 
        $edit_id
    );

    
    $run = $update->execute();
    if ($run) {
        echo "<script>alert('Product updated successfully!');</script>";
        echo "<script>window.location.href='index.php?view_products';</script>";
    } else {
        echo "<script>alert('Error updating product.');</script>";
    }
}
?>