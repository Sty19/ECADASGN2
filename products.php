<?php
include_once("db_config.php");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize filters
$filters = [];

// Check if a category filter is applied
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $categoryID = (int) $_GET['category'];
    $filters[] = "p.ProductID IN (SELECT ProductID FROM catproduct WHERE CategoryID = $categoryID)";
}

// Check if a search query is applied
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']);
    $filters[] = "(p.ProductTitle LIKE '%$searchTerm%' OR p.ProductDesc LIKE '%$searchTerm%')";
}

// Check if price range filter is applied
if ((isset($_GET['min_price']) && $_GET['min_price'] !== '') || (isset($_GET['max_price']) && $_GET['max_price'] !== '')) {
    $minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? floatval($_GET['min_price']) : 0;
    $maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? floatval($_GET['max_price']) : 999999;
    $filters[] = "(p.Price BETWEEN $minPrice AND $maxPrice)";
}

// Check if "on offer" filter is applied
if (isset($_GET['on_offer']) && $_GET['on_offer'] == '1') {
    $currentDate = date("Y-m-d");
    $filters[] = "(p.Offered = 1 AND p.OfferStartDate <= '$currentDate' AND p.OfferEndDate >= '$currentDate')";
}

// Build WHERE clause correctly
$filterQuery = !empty($filters) ? "WHERE " . implode(" AND ", $filters) : "";

// Fetch products with all applied filters
$query = "SELECT p.* FROM product p $filterQuery ORDER BY p.ProductTitle ASC";
$result = $conn->query($query);
$products = $result->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ECAD Store</title>
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/product.css">

    <!-- Box Icons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

</head>
<body>
    <!-- Header Section -->
    <header>
        <?php include 'header.php'; ?>
    </header>

    <!-- Header Section End -->
    &nbsp;
    <section class="search-bar">
    <form method="GET" action="products.php">
        <input type="text" name="search" placeholder="Search for products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
        
        <div class="checkbox-container">
            <label>
                <input type="checkbox" name="on_offer" value="1" <?php echo isset($_GET['on_offer']) && $_GET['on_offer'] == '1' ? 'checked' : ''; ?>> On Offer
            </label>
        </div>
        &nbsp;&nbsp;&nbsp;
        <button type="submit">Search</button>
    </form>
</section>


    <section class="product-list">
        <h1>Available Products</h1>
        <div class="products">
            <?php if (empty($products)) : ?>
                <p>No products found.</p>
            <?php else : ?>
                <?php foreach ($products as $product) : ?>
                    <div class="product">
                        <?php
                        // Get image filename from database
                        $imageName = $product['ProductImage'];
                        $imagePath = "ECAD2024Oct_Assignment_1_Input_Files/Images/Products/" . $imageName;
                        
                        // Check if the image file exists
                        if (file_exists($imagePath)) {
                            echo "<img src='$imagePath' alt='{$product['ProductTitle']}' width='200'>";
                        } else {
                            echo "<img src='images/default.jpg' alt='No Image' width='200'>";
                        }
                        ?>
                        <h3><?php echo $product['ProductTitle']; ?></h3>
                        
                        <?php
                        // Check if the product is on offer and within offer date range
                        $currentDate = date("Y-m-d");
                        if ($product['Offered'] == 1 && $product['OfferStartDate'] <= $currentDate && $product['OfferEndDate'] >= $currentDate) {
                            echo "<p class='offer-price'>On Offer: $" . number_format($product['OfferedPrice'], 2) . "</p>";
                            echo "<p class='original-price'><s>Original Price: $" . number_format($product['Price'], 2) . "</s></p>";
                        } else {
                            echo "<p>Price: $" . number_format($product['Price'], 2) . "</p>";
                        }
                        ?>
                        
                        <?php if ($product['Quantity'] <= 0): ?>
                            <p class="out-of-stock">Out of Stock</p>
                            <button disabled>Add to Cart</button>
                        <?php else: ?>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                                <input type="number" name="quantity" value="1" min="1">
                                <button type="submit">Add to Cart</button>
                            </form>
                        <?php endif; ?>
                        
                        <a href="product_details.php?id=<?php echo $product['ProductID']; ?>">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>


