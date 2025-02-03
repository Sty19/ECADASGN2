<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ecadasgn1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all categories
$result = $conn->query("SELECT * FROM category ORDER BY CatName ASC");
$categories = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - ECAD Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Product Categories</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
        </nav>
    </header>

    <section class="category-list">
        <h2>Browse by Category</h2>
        <div class="categories">
            <?php foreach ($categories as $category) : ?>
                <div class="category">
                    <?php
                    // Generate category image path
                    $imageName = $category['CatImage'];
                    $imagePath = "ECAD2024Oct_Assignment_1_Input_Files/Images/Category/" . $imageName;
                    
                    // Check if the image file exists
                    if (file_exists($imagePath)) {
                        echo "<img src='$imagePath' alt='{$category['CatName']}' width='200'>";
                    } else {
                        echo "<img src='images/default.jpg' alt='No Image' width='200'>";
                    }
                    ?>
                    <h3><a href="products.php?category=<?php echo $category['CategoryID']; ?>">
                        <?php echo $category['CatName']; ?>
                    </a></h3>
                    <p><?php echo $category['CatDesc']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>