<?php
if(!isset($page_title)){
    $page_title ="E-Commerce";
}

$path_prefix = "";
// Check if the current file is inside the 'users_area' directory
$is_user_area = (strpos($_SERVER['PHP_SELF'], 'users_area') !== false);

if($is_user_area){
    $path_prefix = "../";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title)?></title>

    <!-- Bootstrap (Fixed double slash typo) -->
    <link rel="stylesheet" href="<?php echo $path_prefix;?>bootstrap/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

    <!-- Main Stylesheet -->
             <link rel="stylesheet" href="<?php echo $path_prefix;?>style.css">

    <?php if($is_user_area){ ?>
        <link rel="stylesheet" href="<?php echo $path_prefix;?>users_area/user_style.css">
    <?php }?>
</head>
<body>
    <?php include('toast_notification.php')?>