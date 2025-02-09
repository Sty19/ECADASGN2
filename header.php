<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BabyJoy e-BookStore</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
</head>

<body>
<header class="header">
    <a href="index.php" class="logo"> <i class="fa-solid fa-baby"></i> BabyJoy</a>

    <nav class="navbar">
    <a href="products.php">
    <i class="fa-solid fa-store"></i> Browse Shop
    </a>
    <a href="cart.php">
    <i class="fa-solid fa-cart-shopping"></i> 
    Cart (<?php echo isset($_SESSION["NumCartItem"]) ? $_SESSION["NumCartItem"] : 0; ?>)
    </a>

        <?php if (isset($_SESSION['ShopperID'])) : ?>
            <a href="categories.php"> <i class="fa-solid fa-layer-group"></i> Categories</a>
            <a href="feedback.php"> <i class="fa-solid fa-comments"></i> Give Feedback</a>
            <a href="profile.php"> <i class="fa-solid fa-user-pen"></i> Edit Profile</a>
            <a href="logout.php"> <i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        <?php else : ?>
            <a href="signup.php"> <i class="fa-solid fa-user"></i> Sign Up</a>
            <a href="login.php"> <i class="fa-solid fa-right-to-bracket"></i> Login</a>
        <?php endif; ?>
    </nav>
    
    <i class="fa-solid fa-user" style="color: white;"></i> &nbsp;&nbsp;
<div class="welcome-message">
  <h4><?php echo isset($_SESSION["ShopperName"]) ? $_SESSION["ShopperName"] : "Guest"; ?></h4>
</div>
</header>
<!-- Header Section End -->

</body>
    
</html>