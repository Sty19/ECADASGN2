<?php

// Database connection
include_once("db_config.php");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch featured products (products currently on offer)
$query = "SELECT ProductID, ProductTitle, Price, OfferedPrice, Quantity, ProductImage 
          FROM product
          WHERE OfferedPrice = 1 AND Quantity > 0 
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
    <meta ProductTitle="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BabyJoy Store</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <?php include 'header.php'; ?>
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
                            <img src="<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="<?php echo htmlspecialchars($product['ProductTitle']); ?>">
                            <h3><?php echo htmlspecialchars($product['ProductTitle']); ?></h3>
                            <p>Price: 
                                <?php if ($product['OfferedPrice'] > 0) : ?>
                                    <span class="original-price">$<?php echo number_format($product['Price'], 2); ?></span>
                                    <span class="discounted-price">$<?php echo number_format($product['OfferedPrice'], 2); ?></span>
                                <?php else : ?>
                                    $<?php echo number_format($product['Price'], 2); ?>
                                <?php endif; ?>
                            </p>
                            <?php if ($product['Quantity'] > 0) : ?>
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