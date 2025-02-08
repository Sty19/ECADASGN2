<?php 
session_start();
include_once("db_config.php"); // Ensure the database connection is initialized

global $conn; // Make sure $conn is accessible globally

if (isset($_SESSION['ShopperID'])) {
    $shopperID = $_SESSION['ShopperID'];

    // Check if the shopper has an existing active cart (OrderPlaced = 0)
    $qry = "SELECT ShopCartID FROM Shopcart WHERE ShopperID = ? AND OrderPlaced = 0 ORDER BY DateCreated DESC LIMIT 1";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $shopperID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['Cart'] = $row['ShopCartID'];
    }

    $stmt->close();
}

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            addItem();
            break;
        case 'update':
            updateItem();
            break;
        case 'remove':
            removeItem();
            break;
    }
}

function addItem() {
    global $conn; // Ensure $conn is available here

    if (!isset($_SESSION["ShopperID"])) {
        header("Location: login.php");
        exit;
    }

    if (!isset($_SESSION["Cart"])) {
        $qry = "INSERT INTO Shopcart (ShopperID) VALUES (?)";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $_SESSION["ShopperID"]);
        $stmt->execute();
        $stmt->close();

        $qry = "SELECT LAST_INSERT_ID() AS ShopCartID";
        $result = $conn->query($qry);
        $row = $result->fetch_array();
        $_SESSION["Cart"] = $row["ShopCartID"];
    }

    $pid = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $qry = "SELECT * FROM ShopCartItem WHERE ShopCartID = ? AND ProductID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $_SESSION["Cart"], $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $qry = "UPDATE ShopCartItem SET Quantity = LEAST(Quantity + ?, 10)
                WHERE ShopCartID = ? AND ProductID = ?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("iii", $quantity, $_SESSION["Cart"], $pid);
        $stmt->execute();
        $stmt->close();
    } else {
        $qry = "INSERT INTO ShopCartItem (ShopCartID, ProductID, Price, Name, Quantity)
                SELECT ?, ?, Price, ProductTitle, ? FROM Product WHERE ProductID = ?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("iiii", $_SESSION["Cart"], $pid, $quantity, $pid);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: cart.php");
    exit;
}

function updateItem() {
    global $conn; // Ensure $conn is available here

    if (!isset($_SESSION["Cart"])) {
        header("Location: login.php");
        exit;
    }

    $cartid = $_SESSION["Cart"];
    $pid = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    $qry = "UPDATE ShopCartItem SET Quantity = ? WHERE ProductID = ? AND ShopCartID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("iii", $quantity, $pid, $cartid);
    $stmt->execute();
    $stmt->close();

    header("Location: cart.php");
    exit();
}

function removeItem() {
    global $conn; // Ensure $conn is available here

    if (!isset($_SESSION["Cart"])) {
        exit;
    }

    $pid = $_POST["product_id"];
    $qry = "DELETE FROM ShopCartItem WHERE ProductID = ? AND ShopCartID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $pid, $_SESSION["Cart"]);
    $stmt->execute();
    $stmt->close();

    header("Location: cart.php");
    exit();
}

function GetTotalItemsInCart($cartID) {
    global $conn;

    $qry = "SELECT SUM(Quantity) AS TotalItems FROM ShopCartItem WHERE ShopCartID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $cartID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['TotalItems'] ? $row['TotalItems'] : 0;
    }
    return 0;
}
?>
