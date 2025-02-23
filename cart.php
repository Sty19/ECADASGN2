<?php 
include_once("cartFunctions.php");
include("header.php");

if (!isset($_SESSION['ShopperID'])) {
    header ("Location: login.php");
    exit;
}

// Check if Cart is set before calling GetTotalItemsInCart
if (isset($_SESSION['Cart'])) {
    $totalItems = GetTotalItemsInCart($_SESSION['Cart']);
    $_SESSION['NumCartItem'] = $totalItems;
} else {
    $totalItems = 0; // Assuming no items if Cart is not set
}

echo "<div id='myShopCart' style='margin:auto; width: 90%; max-width: 1200px;'>";
if (isset($_SESSION["Cart"])) {
    include_once("db_config.php");
    $qry = "SELECT *, (Price * Quantity) AS Total FROM ShopCartItem WHERE ShopCartID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $_SESSION["Cart"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        echo "<p class='page-title' style='text-align:center; font-size:24px;'>Shopping Cart</p>";
        echo "<div class='table-responsive'>"; 
        echo "<table class='table table-hover' style='width: 100%;'>"; 
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
            echo "<td style='width:50%'>{$row['Name']} <br /></td>";
            echo "<td>" . number_format($row["Price"], 2) . "</td>";
            echo "<td>";
            echo "<form action='cartFunctions.php' method='post'>";
            echo "<select name='quantity' onChange='this.form.submit()'>";
            for ($i = 1; $i <= 10; $i++) {
                $selected = ($i == $row["Quantity"]) ? "selected" : ""; 
                echo "<option value='$i' $selected>$i</option>";
            }
            echo "</select>";
            echo "<input type='hidden' name='action' value='update'/>"; 
            echo "<input type='hidden' name='product_id' value='{$row['ProductID']}'/>";
            echo "</form>";
            echo "</td>";
            echo "<td>" . number_format($row["Total"], 2) . "</td>";
            echo "<td>";
            echo "<form action='cartFunctions.php' method='post'>"; 
            echo "<input type='hidden' name='action' value='remove' />"; 
            echo "<input type='hidden' name='product_id' value='{$row['ProductID']}'/>"; 
			echo "<button type='submit' class='remove-item-btn'><i class='fa-solid fa-trash-can'></i> Remove Item</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
            $_SESSION["Items"][] = array("productId"=>$row["ProductID"], "name"=>$row["Name"], "price"=>$row["Price"], "quantity"=>$row["Quantity"]);
            $subTotal += $row["Total"];
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        $_SESSION["SubTotal"] = round($subTotal, 2);
        echo "<p style='text-align:right; font-size:20px'><strong>Subtotal:</strong> S$" . number_format($subTotal, 2) . "<br>Total Items: $totalItems</p>";
        echo "<form method='post' action='checkoutProcess.php'>";
        echo "<div style='float:right; text-align:right; margin-top:20px; width: 300px;'>"; 
        echo "<h4>Delivery Mode:</h4>";
        
        // Delivery charge logic - free delivery if subtotal is above 200
        $shippingCharge = ($_SESSION['SubTotal'] > 200) ? 0 : 5;
        
        // Set shipping options based on subtotal
        if ($_SESSION['SubTotal'] > 200) {
            $deliveryOptions = [
                "0" => "Free Delivery ($0)",
                "10" => "Express Delivery (S$10)"
            ];
        } else {
            $deliveryOptions = [
                "5" => "Normal Delivery (S$5)",
                "10" => "Express Delivery (S$10)"
            ];
        }
        
        echo "<select id='deliveryMode' name='deliveryMode' required style='padding:5px; margin-bottom:10px;' onchange='updateTotal()'>";
        foreach ($deliveryOptions as $value => $label) {
            echo "<option value='$value'>$label</option>";
        }
        echo "</select><br>";
        
        // Update final total with shipping charge
        $finalTotal = $_SESSION['SubTotal'] + $shippingCharge;
        echo "<p id='finalTotal'><strong>Total:</strong> S$" . number_format($finalTotal, 2) . "</p>";
        echo "<input type='submit' value='Proceed to Checkout' style='padding:10px 20px; background-color:#28a745; color:white; border:none; border-radius:5px; cursor:pointer; width:100%;'>"; 
        echo "</div>";
        echo "</form>";
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

<script>
function updateTotal() {
    var deliveryFee = parseInt(document.getElementById('deliveryMode').value);
    var subtotal = <?php echo $_SESSION['SubTotal']; ?>;
    
    // Calculate total price based on selected delivery option
    var finalTotal = subtotal + deliveryFee;

    // Update the final total in the HTML
    document.getElementById('finalTotal').innerHTML = '<strong>Total:</strong> S$' + finalTotal.toFixed(2);
}
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta ProductTitle="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BabyJoy Store</title>
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/cart.css">

    <!-- Box Icons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

</head>
<body>


</body>
</html>