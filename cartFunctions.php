<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "your_database_name");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getCartItem($ShopperID, $ProductID) {
    global $conn;
    $qry = "SELECT * FROM ShopperCart WHERE ShopperID = ? AND ProductID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $ShopperID, $ProductID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function addCartItem($ShopperID, $ProductID, $Quantity) {
    global $conn;
    $existingItem = getCartItem($ShopperID, $ProductID);

    if ($existingItem) {
        $newQuantity = $existingItem['Quantity'] + $Quantity;
        updateCartItem($ShopperID, $ProductID, $newQuantity);
    } else {
        $qry = "INSERT INTO ShopperCart (ShopperID, ProductID, Quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("iii", $ShopperID, $ProductID, $Quantity);
        $stmt->execute();
    }
}

function updateCartItem($ShopperID, $ProductID, $Quantity) {
    global $conn;
    $qry = "UPDATE ShopperCart SET Quantity = ? WHERE ShopperID = ? AND ProductID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("iii", $Quantity, $ShopperID, $ProductID);
    $stmt->execute();
}

function removeCartItem($ShopperID, $ProductID) {
    global $conn;
    $qry = "DELETE FROM ShopperCart WHERE ShopperID = ? AND ProductID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $ShopperID, $ProductID);
    $stmt->execute();
}

function getCartItems($ShopperID) {
    global $conn;
    $qry = "SELECT p.ProductName, p.Price, sc.ProductID, sc.Quantity 
            FROM ShopperCart sc 
            JOIN Product p ON sc.ProductID = p.ProductID 
            WHERE sc.ShopperID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $ShopperID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getTotalItemsInCart($ShopperID) {
    global $conn;
    $qry = "SELECT SUM(Quantity) AS TotalItems FROM ShopperCart WHERE ShopperID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $ShopperID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['TotalItems'] ?? 0;
}

function getCartSubtotal($ShopperID) {
    global $conn;
    $qry = "SELECT SUM(p.Price * sc.Quantity) AS Subtotal 
            FROM ShopperCart sc 
            JOIN Product p ON sc.ProductID = p.ProductID 
            WHERE sc.ShopperID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $ShopperID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['Subtotal'] ?? 0;
}

function getDeliveryCharge($ShopperID) {
    $subtotal = getCartSubtotal($ShopperID);
    return ($subtotal > 200) ? 0 : 10; // Waive delivery charge if subtotal > $200
}

function getCartTotal($ShopperID) {
    return getCartSubtotal($ShopperID) + getDeliveryCharge($ShopperID);
}
?>