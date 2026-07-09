<?php
include("./includes/connect.php");
include("./includes/common_function.php");
include("./includes/header.php");
?>
<script>
  document.title = 'E-Home'
</script>

<!-- navbar -->
<?php include("./includes/navbar.php");
?>

<!-- homepage -->
<div class="container my-4">
  <div class="hero-banner">Find the Perfect Gift For Every Occasion</div>
</div>
<div class="container">
  <div class="row">
    <div class="col-lg-3 mb-4 sidebar">
      <h4>Occasion</h4>
      <ul class="navbar-nav">
        <?php get_occasion(); ?>
      </ul>
      <h4>Gift Categories </h4>
      <ul class="navbar-nav">
        <?php get_gift_categories(); ?>
      </ul>
    </div>
    <div class="col-lg-9">
      <div class="row g-4">
        
      <?php getproducts();
 get_categories_unique();
 get_occasions_unique();
      ?>

      </div>
    </div>
  </div>
</div>

<!-- foot0er -->
<?php include("./includes/footer.php") ?>;
</body>

</html>