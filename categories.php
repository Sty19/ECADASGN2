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
    <meta ProductTitle="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BabyJoy Store</title>
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/collection.css">

    <!-- Box Icons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

</head>
<body>
<header>
    <?php include 'header.php'; ?>
</header>

    <!-- Categories Section -->
<section class="section collection">
  <div class="container">
    <ul class="collection-list has-scrollbar">

      <?php foreach ($categories as $category) : ?>
        <?php
          // Generate category image path
          $imageName = $category['CatImage'];
          $imagePath = "ECAD2024Oct_Assignment_1_Input_Files/Images/Category/" . $imageName;
          
          // Check if the image file exists, fallback to a default image
          if (!file_exists($imagePath)) {
              $imagePath = "ECAD2024Oct_Assignment_1_Input_Files/Images/default.jpg";
          }
        ?>

        <li class="category-item">
          <div class="collection-card" style="background-image: url('<?= $imagePath ?>')">
            <div class="card-content">
              <h3 class="h4 card-title"><?= htmlspecialchars($category['CatName']) ?></h3>
              <a href="products.php?category=<?= urlencode($category['CategoryID']) ?>" class="btn btn-secondary">
                <span>Explore All</span>
                <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
              </a>
            </div>
          </div>

          <!-- Description Below the Card -->
          <p class="category-desc"><?= htmlspecialchars($category['CatDesc']) ?></p>

        </li>

      <?php endforeach; ?>

    </ul>
  </div>
</section>

<!-- Improved Styling -->
<style>
  .collection-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px; /* Adds spacing between items */
    justify-content: center;
    padding: 20px 0;
  }

  .category-item {
    width: 300px; /* Adjust width for a cleaner grid layout */
    list-style: none;
    text-align: center; /* Center description text */
  }

  .collection-card {
    position: relative;
    width: 100%;

    background-size: cover;
    background-position: center;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    padding: 20px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
  }

  .card-content {
    color: white;
    padding: 15px;
    border-radius: 8px;
    width: 100%;
    text-align: center;
  }

  .category-desc {
    font-size: 14px;
    margin-top: 10px; /* Add spacing between card and description */
    padding: 10px;
    background: #f8f8f8;
    border-radius: 5px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    color: #333;
  }

  .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    color: white;
    background-color: #ff6600;
    padding: 8px 15px;
    border-radius: 5px;
    transition: 0.3s ease-in-out;
  }

  .btn-secondary:hover {
    background-color: #e05500;
  }
</style>


</body>
</html>