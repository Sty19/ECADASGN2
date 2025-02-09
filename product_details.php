<?php
// Database connection
include_once("db_config.php");

// Get the product ID from the URL
$productID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM product WHERE ProductID = ?");
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();  // Make sure data is fetched before closing
$stmt->close(); // Close only after data is fully retrieved
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['ProductTitle']; ?> - Product Details</title>
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/product_details.css">

    <!-- Box Icons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

</head>
<header>
    <?php include 'header.php'; ?>
</header>
<body>

<div class="product-container">
    <div class="product-image">
        <img src="ECAD2024Oct_Assignment_1_Input_Files/Images/Products/<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="<?php echo htmlspecialchars($product['ProductTitle']); ?>">
    </div>
    <div class="product-info">
        <h1><?php echo htmlspecialchars($product['ProductTitle']); ?></h1>
        <p class="price">
            <?php if ($product['OfferedPrice'] > 0): ?>
                <span class="original-price">$<?php echo number_format($product['Price'], 2); ?></span>
                <span class="discounted-price">$<?php echo number_format($product['OfferedPrice'], 2); ?></span>
            <?php else: ?>
                $<?php echo number_format($product['Price'], 2); ?>
            <?php endif; ?>
        </p>
        <p class="description"><?php echo htmlspecialchars($product['ProductDesc']); ?></p>
        <form action="cartFunctions.php" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?php echo $productID; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $product['Quantity']; ?>" required>
            <button type="submit" class="btn-add-to-cart">Add to Cart</button>
        </form>

        <script>
            function validateForm() {
                var quantity = document.getElementById('quantity').value;
                if (quantity < 1) {
                    alert("Item is out of Stock!");
                    return false;
                }
                return true;
            }
        </script>
        <a href="products.php" class="back-link">← Back to Products</a>
    </div>
</div>

</body>
</html>
