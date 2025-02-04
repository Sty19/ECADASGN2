<?php
session_start();
require_once 'cartFunctions.php';

// Ensure the shopper is logged in
if (!isset($_SESSION['ShopperID'])) {
    die("You must be logged in to view or modify the cart.");
}

$ShopperID = $_SESSION['ShopperID'];

// Handle actions: Add, Update, Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $ProductID = $_POST['ProductID'] ?? null;
        $Quantity = $_POST['Quantity'] ?? null;

        switch ($action) {
            case 'add':
                addCartItem($ShopperID, $ProductID, $Quantity);
                break;
            case 'update':
                updateCartItem($ShopperID, $ProductID, $Quantity);
                break;
            case 'delete':
                removeCartItem($ShopperID, $ProductID);
                break;
        }
    }
}

// Fetch cart items for display
$cartItems = getCartItems($ShopperID);
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