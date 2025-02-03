<?php
include 'db_config.php';  // Include database connection

$sql = "SELECT * FROM products";  // Fetch all products
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalogue</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Product Catalogue</h1>

<div class="product-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<h2>" . $row["name"] . "</h2>";
            echo "<p>Price: $" . $row["price"] . "</p>";
            echo "<a href='product_details.php?id=" . $row["id"] . "'>View Details</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>

</body>
</html>

<?php
$conn->close();  // Close connection
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
        </nav>
    </header>

    <section class="featured-products">
        <h2>Featured Products</h2>
        <div class="product-list">
            <?php foreach ($featured_products as $product) : ?>
                <div class="product">
                    <h3><?php echo $product['Name']; ?></h3>
                    <p>Price: $<?php echo number_format($product['Price'], 2); ?></p>
                    <a href="product_details.php?id=<?php echo $product['ProductID']; }">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
