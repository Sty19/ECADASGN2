<?php
// Database connection
include_once("db_config.php");
// Fetch featured products (limit to 4 for homepage)
$result = $conn->query("SELECT * FROM product ORDER BY RAND() LIMIT 4");
$featured_products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - ECAD Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to ECAD Store</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <section class="featured-products">
        <h2>Featured Products</h2>
        <div class="product-list">
            <?php foreach ($featured_products as $product) : ?>
                <div class="product">
                    <h3><?php echo $product['Name']; ?></h3>
                    <p>Price: $<?php echo number_format($product['Price'], 2); ?></p>
                    <a href="product_details.php?id=<?php echo $product['ProductID'];?>">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
