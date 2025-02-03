<?php
session_start(); // Start the session to access session variables

// Database connection
include_once("db_config.php");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch featured products (products currently on offer)
$query = "SELECT ProductID, Name, Price, OfferedPrice, Inventory, ImageURL 
          FROM products 
          WHERE Offered = 1 AND Inventory > 0 
          ORDER BY RAND() LIMIT 4";
$result = $conn->query($query);
$featured_products = $result->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BabyJoy Store</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="welcome-message">
            <h1>Welcome To BabyJoy Store</h1>
            <p>We are having sales from 1 Feb to 21 Feb!</p>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Browse Shop</a>
            <a href="cart.php">Cart (<?php echo isset($_SESSION["NumCartItem"]) ? $_SESSION["NumCartItem"] : 0; ?>)</a>
            <?php if (isset($_SESSION['ShopperID'])) : ?>
                <a href="logout.php">Logout</a>
            <?php else : ?>
                <a href="login.php">Sign Up / Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Featured Products Section -->
        <section class="featured-products">
            <h2>Featured Products (On Offer)</h2>
            <div class="product-list">
                <?php if (!empty($featured_products)) : ?>
                    <?php foreach ($featured_products as $product) : ?>
                        <div class="product">
                            <img src="<?php echo htmlspecialchars($product['ImageURL']); ?>" alt="<?php echo htmlspecialchars($product['Name']); ?>">
                            <h3><?php echo htmlspecialchars($product['Name']); ?></h3>
                            <p>Price: 
                                <?php if ($product['OfferedPrice'] > 0) : ?>
                                    <span class="original-price">$<?php echo number_format($product['Price'], 2); ?></span>
                                    <span class="discounted-price">$<?php echo number_format($product['OfferedPrice'], 2); ?></span>
                                <?php else : ?>
                                    $<?php echo number_format($product['Price'], 2); ?>
                                <?php endif; ?>
                            </p>
                            <?php if ($product['Inventory'] > 0) : ?>
                                <a href="add_to_cart.php?ProductID=<?php echo $product['ProductID']; ?>">Add to Cart</a>
                            <?php else : ?>
                                <p><strong>Out of Stock</strong></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No featured products available at the moment.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="categories">
    <h2>Our Categories</h2>
    <ul>
        <li><a href="products.php?category=Baby Clothing">Baby Clothing</a></li>
        <li><a href="products.php?category=Toys & Accessories">Toys & Accessories</a></li>
        <li><a href="products.php?category=Diapers & Essentials">Diapers & Essentials</a></li>
    </ul>
</section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2025 BabyJoy Store. All rights reserved.</p>
    </footer>
</body>
</html>