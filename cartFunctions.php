<?php 
session_start();

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
	if (! isset($_SESSION["ShopperID"])) {
        echo $_SESSION["ShopperID"];
		//header ("Location: login.php");
		//exit;
	}
	include_once("db_config.php");
	if (! isset($_SESSION["Cart"])){
		$qry = "INSERT INTO Shopcart(ShopperID) VALUES(?)";
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
	$qry = "SELECT * FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("ii", $_SESSION["Cart"], $pid);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$addNewItem = 0;
	if ($result->num_rows > 0){
		$qry = "UPDATE ShopCartItem SET Quantity=LEAST(Quantity+?, 10)
				WHERE ShopCartID=? AND ProductID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("iii", $quantity, $_SESSION["Cart"], $pid);
		$stmt->execute();
		$stmt->close();
	}
	else { 
		$qry = "INSERT INTO ShopCartItem(ShopCartID, ProductID, Price, Name, Quantity)
				SELECT ?, ?, Price, ProductTitle, ? FROM Product WHERE ProductID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("iiii", $_SESSION["Cart"], $pid, $quantity, $pid);
		$stmt->execute();
		$stmt->close();
		$addNewItem = 1;
	}

  	$conn->close();
	if(isset($_SESSION["NumCartItem"])){
		$_SEESION["NumCartItem"] = $_SESSION["NumCartItem"] + $addNewItem;
	}
	else{
		$_SESSION["NumCartItem"] = 1;
	}
	header("Location: cart.php");
	exit;
}

function updateItem() {
	if (! isset($_SESSION["Cart"])) {
		header ("Location: login.php");
		exit;
	}
	$cartid = $_SESSION["Cart"];
	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	include_once("db_config.php");
	$qry = "UPDATE ShopCartItem SET Quantity=? WHERE ProductID=? AND ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("iii", $quantity, $pid, $cartid);
	$stmt->execute();
	$stmt->close();
	$conn->close();
	header("Location: cart.php");
	exit();
}

function removeItem() {
	if (! isset($_SESSION["Cart"])) {
		include_once("db_config.php");
		exit;
	}

}		
?>
