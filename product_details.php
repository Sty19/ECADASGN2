<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ecadasgn1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$result = $conn->query("SELECT * FROM product");
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ECAD Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Our Products</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
        </nav>
    </header>

    <section class="product-list">
        <h2>Available Products</h2>
        <div class="products">
            <?php foreach ($products as $product) : ?>
                <div class="product">
                    <h3><?php echo $product['ProductTitle']; ?></h3>
                    <p>Price: $<?php echo number_format($product['Price'], 2); ?></p>
                    <a href="product_details.php?id=<?php echo $product['ProductID']; ?>">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
