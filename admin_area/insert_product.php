<div class="container mt-4">
    <h2 class="mb-4">
        <!-- Text color uses your CSS secondary-color variable -->
        <i class="fa-solid fa-plus-circle" style="color: var(--secondary-color);"></i> Insert New Product
    </h2>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- enctype is REQUIRED for handling file/image uploads -->
                    <form action="" method="post" enctype="multipart/form-data">
                        
                        <!-- Product Title -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fa-solid fa-tag"></i> Product Title</label>
                            <input type="text" name="product_title" class="form-control" placeholder="Example: Birthday Box" required>
                        </div>

                        <!-- Product Description -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fa-solid fa-align-left"></i> Product Description</label>
                            <input type="text" name="product_description" class="form-control" placeholder="Enter detailed product description" required>
                        </div>

                        <!-- Product Keywords -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fa-solid fa-key"></i> Product Keywords</label>
                            <input type="text" name="product_keywords" class="form-control" placeholder="Example: Birthday gift, celebration (comma separated)" required>
                        </div>

                        <!-- Category & Occasion Grid Row -->
                        <div class="row mb-3">
                            <!-- Category Dropdown -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fa-solid fa-folder-open"></i> Category</label>
                                <select name="product_category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <!-- Dynamic categories will be fetched here via PHP later -->
                                </select>
                            </div>
                            
                            <!-- Occasion Dropdown -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fa-solid fa-calendar-days"></i> Occasion</label>
                                <select name="product_occasion" class="form-select" required>
                                    <option value="">Select Occasion</option>
                                    <!-- Dynamic occasions will be fetched here via PHP later -->
                                </select>
                            </div>
                        </div>

                        <!-- Product Price -->
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fa-solid fa-indian-rupee-sign"></i> Product Price</label>
                            <input type="number" name="product_price" class="form-control" placeholder="Enter product price" min="1" required>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="fa-solid fa-images"></i> Product Images</h5>

                        <!-- Primary Image (Required) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Primary Image (Required)</label>
                            <input type="file" name="product_image1" class="form-control" accept="image/*" required>
                            <div class="form-text text-muted">This will be the main display image.</div>
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
                                <i class="fa-solid fa-circle-check"></i> Insert Product
                            </button>
                            <a href="index.php?view_products" class="btn btn-secondary px-4 ms-2">
                                <i class="fa-solid fa-circle-xmark"></i> Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>