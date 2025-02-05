<?php
session_start();
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
     <!-- Header Section Start -->
<header class="header">
    <a href="index.php" class="logo">BabyJoy</a>

    <nav class="navbar">
        <a href="products.php">Browse Shop</a>
        <a href="cart.php">Cart (<?php echo isset($_SESSION["NumCartItem"]) ? $_SESSION["NumCartItem"] : 0; ?>)</a>
        
        <?php if (isset($_SESSION['ShopperID'])) : ?>
            <a href="feedback.php">Give Feedback</a>
            <a href="profile.php">Edit Profile</a>
            <a href="logout.php">Logout</a>
        <?php else : ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
    <div class="welcome-message">
                <h4><?php echo isset($_SESSION["ShopperName"]) ? $_SESSION["ShopperName"] : "Guest"; ?></h4>
            </div>
</header>
<!-- Header Section End -->

</body>
    
</html>