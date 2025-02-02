<?php
// Start session to manage cart
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "ecad_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding product to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    $result = $conn->query("SELECT * FROM products WHERE ProductID = $product_id");
    $product = $result->fetch_assoc();

    if ($product) {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['Name'],
            'price' => $product['Price'],
            'quantity' => $quantity
        ];
    }
}

// Handle removing product from cart
if (isset($_GET['remove'])) {
    $remove_id = (int) $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
}

// Retrieve cart items
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ECAD Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Shopping Cart</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
        </nav>
    </header>

    <section class="cart">
        <h2>Your Cart</h2>
        <?php if (empty($cart_items)) : ?>
            <p>Your cart is empty.</p>
        <?php else : ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($cart_items as $id => $item) : ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td><a href="cart.php?remove=<?php echo $id; ?>">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        <?php endif; ?>
    </section>
</body>
</html>