<?php
include("./includes/connect.php");
include("./functions/common_function.php");
include("./includes/header.php");
?>
<script>
    document.title = 'Product Details'
</script>

<!-- navbar -->
<?php include("./includes/navbar.php"); 
?>

<div class="container my-4">
    <div class="hero-banner">Product Details</div>
</div>
<div class="container my-5">
    <div class="row">
        <?php 
        view_details();
        ?>
    </div>
</div>

<!-- javascript -->
<script>
    function changeImage(newSrc) {
        const maintImage = document.querySelector('.product-main-image');
        if (maintImage) {
            maintImage.src = newSrc;
        }
    }
</script>

<?php include("./includes/footer.php") ?>;

</body>

</html>