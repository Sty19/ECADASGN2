<?php 
include_once("cartFunctions.php");
include("header.php");

if (!isset($_SESSION['ShopperID'])) {
	header ("Location: login.php");
	exit;
}

echo "<div id='myShopCart' style='margin:auto'>";
if (isset($_SESSION["Cart"])) {
	include_once("db_config.php");
	$qry = "SELECT *, (Price * Quantity) AS Total
			FROM ShopCartItem WHERE ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["Cart"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	if ($result->num_rows > 0) {
		echo "<p class='page-title' style='text-align:center'>Shopping Cart</p>"; 
		echo "<div class='table-responsive' >"; 
		echo "<table class='table table-hover'>";
		echo "<thead class='cart-header'>";
		echo "<tr>";
		echo "<th width='250px'>Item</th>";
		echo "<th width='90px'>Price</th>";
		echo "<th width='60px'>Quantity</th>";
		echo "<th width='120px'>Total (S$)</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>"; 
		echo "</thead>"; 
		$subTotal = 0; 
		echo "<tbody>";
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td style='width:50%'>$row[Name] <br />";
			echo "Product ID: $row[ProductID]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			echo "<td>$formattedPrice</td>";
			echo "<td>";
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<select name='quantity' onChange='this.form.submit()'>";
			for ($i = 1; $i <= 10; $i++){
				if($i == $row["Quantity"]){
					$selected = "selected";
				}
				else{
					$selected = ""; 
				}
				echo "<option value='$i' $selected>$i</option>";
			}
			echo "</select>";
			echo "<input type='hidden' name='action' value='update'/>";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
			echo "</form>";
			echo "</td>";
			$formattedTotal = number_format($row["Total"], 2);
			echo "<td>$formattedTotal</td>";
			echo "<td>";
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='remove' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
			echo "<input type='image' src='/img/trash-can.png' title='Remove Item'/>";
			echo "</form>";
			echo "</td>";
			echo "</tr>";
				$_SESSION["Items"][] = array("productId"=>$row["ProductID"],
				"name"=>$row["Name"],
				"price"=>$row["Price"],
				"quantity"=>$row["Quantity"]);
			$subTotal += $row["Total"];
		}
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
				
		echo "<p style='text-align:right'; font-size:20px'>
				Subtotal = S$".number_format($subTotal, 2);
		$_SESSION["SubTotal"] = round($subTotal, 2);
		// Delivery mode selection and checkout button
		echo "<form method='post' action='checkoutProcess.php'>";
		echo "<div style='float:right; text-align:right; margin-top:20px'>";
		echo "<h4>Delivery Mode:</h4>";
		echo "<select name='deliveryMode' required style='padding:5px; margin-bottom:10px;'>";
		echo "<option value='Normal'>Normal Delivery (\$5)</option>";
		echo "<option value='Express'>Express Delivery (\$10)</option>";
		echo "</select>";
		echo "<br>";
		echo "<input type='submit' value='Proceed to Checkout' 
		            style='padding:10px 20px; background-color:#28a745; color:white; border:none; border-radius:5px; cursor:pointer;'>";
		echo "</div>";
		echo "</form></p>";
	}
	else {
		echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close();
}
else {
	echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
echo "</div>";
include("footer.php");
?>
