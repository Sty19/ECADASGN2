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
    <div class="welcome-message">
        <h1>Welcome To BabyJoy Store, <?php echo isset($_SESSION["ShopperName"]) ? $_SESSION["ShopperName"] : "Guest"; ?></h1>
        <p>We are having sales from 1 Feb to 22 Feb!</p>
    </div>
    <nav>
        <a href="index.php">Home</a>
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
     <!-- Home Section Start -->
	<section class="home" id="home">
		<div class="main-text">
            <h5 data-text= "Summer Collection">Baby Essentials Collection</h5>
            
            <h1>Everything You Need</h1>
			&nbsp;&nbsp;<h2>for Your Little One</h2>
             
            
			<p>Explore our wide selection of adorable baby clothing, fun toys, and must-have essentials to keep your baby happy, comfortable, and stylish.</p>
            <a href="products.php" class="main-btn">Explore Now <i class='bx bx-right-arrow-alt'></i></a>
        </div>
        </div>
	</section>
    <!-- Home Section End -->
</body>
    
</html>