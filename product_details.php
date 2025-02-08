<?php
// Database connection
include_once("db_config.php");

// Get the product ID from the URL
$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database
$query = "SELECT ProductTitle, ProductDesc, ProductImage, Price FROM product WHERE ProductID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['ProductTitle']; ?> - Product Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS -->
</head>
<body>
    <h1><?php echo $product['ProductTitle']; ?></h1>
    <div class="product-details">
        <img src="ECAD2024Oct_Assignment_1_Input_Files/Images/Products/<?php echo $product['ProductImage']; ?>" alt="<?php echo $product['ProductTitle']; ?>" width="300">
        <p><strong>Description:</strong> <?php echo $product['ProductDesc']; ?></p>
        <p><strong>Price:</strong> $<?php echo number_format($product['Price'], 2); ?></p>

        <form action="cartFunctions.php" method="post">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?php echo $productID; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="1" min="1" max="10">
            <button type="submit">Add to Cart</button>
        </form>
    </div>
    <a href="products.php">← Back to Products</a>
</body>
</html>