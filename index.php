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
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/feature.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/collection.css">


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
    <div class="sale-banner">
  We are having sales from 1 Feb to 21 Feb!
    </div>

    <!-- Main Content -->
    <main>
       <!-- Home Section Start -->
       <section class="home" id="home">
        <h1>Create a cozy and joyful world for your little one today!</h1>
        <p>We specialize in providing adorable baby clothing, fun toys, and must-have essentials to keep your baby happy, comfortable, and stylish.</p>
        <a href="products.php" class="btn">Explore Now</a>
        </section>
    <!-- Home Section End -->

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
        <!-- Featured Products Section -->
        <!-- Categories Section -->
<section class="section collection">
  <div class="container">
    <ul class="collection-list has-scrollbar">

      <li>
        <div class="collection-card" style="background-image: url('ECAD2024Oct_Assignment_1_Input_Files/Images/babyclothing.jpg')">
          <div class="card-content">
            <h3 class="h4 card-title">Baby Clothing</h3>
            <a href="products.php?category=Baby Clothing" class="btn btn-secondary">
              <span>Explore All</span>
              <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </div>
        </div>
      </li>

      <li>
        <div class="collection-card" style="background-image: url('ECAD2024Oct_Assignment_1_Input_Files/Images/toys.jpg')">
          <div class="card-content">
            <h3 class="h4 card-title">Toys & Accessories</h3>
            <a href="products.php?category=Toys & Accessories" class="btn btn-secondary">
              <span>Explore All</span>
              <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </div>
        </div>
      </li>

      <li>
        <div class="collection-card" style="background-image: url('ECAD2024Oct_Assignment_1_Input_Files/Images/diapers.jpg')">
          <div class="card-content">
            <h3 class="h4 card-title">Diapers & Essentials</h3>
            <a href="products.php?category=Diapers & Essentials" class="btn btn-secondary">
              <span>Explore All</span>
              <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </div>
        </div>
      </li>

    </ul>
  </div>
</section>
<!-- Categories Section End -->

    </main>
    <!-- Footer Section -->
    <footer>
        <p>&copy; 2025 BabyJoy Store. All rights reserved.</p>
    </footer>

    <script src="ECAD2024Oct_Assignment_1_Input_Files/js/custom.js"></script>
</body>
</html>