<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ecadasgn1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Ensure user is logged in before accessing cart
if (!isset($_SESSION['ShopperID'])) {
    die("Please log in to access the shopping cart.");
}

$shopperID = $_SESSION['ShopperID'];

// Fetch cart items for the logged-in shopper
$query = "SELECT si.ProductID, si.Price, si.Name, si.Quantity, p.ProductImage FROM shopcartitem si 
          JOIN product p ON si.ProductID = p.ProductID 
          JOIN shopcart sc ON si.ShopCartID = sc.ShopCartID 
          WHERE sc.ShopperID = ? AND sc.OrderPlaced = 0";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $shopperID);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate total items in cart
$totalItems = array_sum(array_column($cartItems, 'Quantity'));

// Calculate subtotal
$subtotal = array_sum(array_map(function($item) {
    return $item['Price'] * $item['Quantity'];
}, $cartItems));

// Shipping charge logic
$shippingCharge = ($subtotal > 200) ? 0 : 5;

// Total calculation
$total = $subtotal + $shippingCharge;
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
            <a href="cart.php">Cart (<?php echo $totalItems; ?>)</a>
        </nav>
    </header>

    <section class="cart">
        <h2>Your Cart</h2>
        <?php if (empty($cartItems)) : ?>
            <p>Your cart is empty.</p>
        <?php else : ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($cartItems as $item) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['Name']); ?></td>
                        <td><img src="ECAD2024Oct_Assignment_1_Input_Files/Images/Products/<?php echo $item['ProductImage']; ?>" width="50"></td>
                        <td>$<?php echo number_format($item['Price'], 2); ?></td>
                        <td>
                            <form method="POST" action="update_cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $item['ProductID']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['Quantity']; ?>" min="1">
                                <button type="submit">Update</button>
                            </form>
                        </td>
                        <td>$<?php echo number_format($item['Price'] * $item['Quantity'], 2); ?></td>
                        <td><a href="remove_cart.php?product_id=<?php echo $item['ProductID']; ?>">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4"><strong>Subtotal:</strong></td>
                    <td><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Shipping Charge:</strong></td>
                    <td><strong>$<?php echo number_format($shippingCharge, 2); ?></strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Total:</strong></td>
                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                    <td></td>
                </tr>
            </table>
            <a href="checkout.php">Proceed to Checkout</a>
        <?php endif; ?>
    </section>
</body>
</html>
