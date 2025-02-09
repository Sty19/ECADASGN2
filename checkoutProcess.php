<?php
session_start();
// Handle PayPal cancellation
if (isset($_GET['cancel'])) {
    unset($_SESSION['Items']);
    unset($_SESSION['Cart']);
    $_SESSION["NumCartItem"] = 0;
    $_SESSION['cancel_message'] = "Payment was canceled. Cart has been reset.";
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?')); // Clean URL
    exit;
}
include("header.php");
include_once("myPayPal.php"); // Make sure this file contains your PayPal configurations
include("db_config.php");
?>

<style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 28px;
            color: #dc3545; /* Red color to indicate cancellation */
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .actions a {
            text-decoration: none;
            color: white;
            background-color: #007bff; /* Blue button */
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out;
        }

        .actions a:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: left;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            .actions {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>

    <div class="container">
        <h1>Payment Cancelled</h1>
        <p>Your payment was not completed. What would you like to do next?</p>

        <!-- Error Message Section (if applicable) -->
        <?php if (isset($errorMessage)): ?>
            <div class="error-message">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="actions">
            <a href="cart.php">Return to Cart</a>
            <a href="products.php">Continue Shopping</a>
        </div>
    </div>


<?php


if ($_POST) {
    if (!isset($_SESSION['Items']) || count($_SESSION['Items']) == 0) {
        echo "No items in cart. Please add items to your cart.";
        include("footer.php");
        exit;
    }


	// Validate stock levels
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name); // Update with your database credentials
    $allItemsAvailable = true;
    $stockIssues = [];

    foreach ($_SESSION['Items'] as $item) {
        $stmt = $conn->prepare("SELECT Quantity FROM product WHERE ProductID = ?");
        $stmt->bind_param("i", $item["productId"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row["Quantity"] < $item["quantity"]) {
                $allItemsAvailable = false;
                $stockIssues[] = $item["name"] . " has only " . $row["Quantity"] . " units available.";
            }
        } else {
            $allItemsAvailable = false;
            $stockIssues[] = $item["name"] . " is not available.";
        }
        $stmt->close();
    }

    if (!$allItemsAvailable) {
        echo "<div class='error-message'>";
        foreach ($stockIssues as $issue) {
            echo "<p>$issue</p>";
        }
        echo "</div>";
        include("footer.php");
        exit;
    }
	
    // Calculate subtotal from session items
    $subTotal = 0;
    foreach ($_SESSION['Items'] as $item) {
        $subTotal += $item["price"] * $item["quantity"];
    }
    $_SESSION["SubTotal"] = $subTotal;

    // Determine shipping charge
    if (isset($_POST['deliveryMode'])) {
        $_SESSION['deliveryMode'] = $_POST['deliveryMode'];  // Ensure it's captured from POST and not just session
        $shippingCharge = ($_POST['deliveryMode'] === '10') ? 10.00 : 5.00;
    } else {
        $shippingCharge = 5.00;  // Default shipping charge if not specified
    }
    $_SESSION["ShipCharge"] = $shippingCharge;

    // Calculate tax (example rate of 9%)
    $_SESSION["Tax"] = round($_SESSION["SubTotal"] * 0.09, 2);

    // Prepare data for PayPal
    $paypal_data = '';
	foreach (array_values($_SESSION['Items']) as $key => $item) {
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $key . '=' . urlencode($item["quantity"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $key . '=' . urlencode($item["price"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $key . '=' . urlencode($item["name"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $key . '=' . urlencode($item["productId"]);
    }

    $finalTotal = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];
    $padata = '&CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
              '&PAYMENTACTION=Sale' .
              '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
              '&PAYMENTREQUEST_0_AMT=' . urlencode($finalTotal) .
              '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($_SESSION["SubTotal"]) .
              '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($_SESSION["ShipCharge"]) .
              '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($_SESSION["Tax"]) .
              '&BRANDNAME=' . urlencode("BabyJoy") .
              $paypal_data .
              '&RETURNURL=' . urlencode($PayPalReturnURL) .
              '&CANCELURL=' . urlencode($PayPalCancelURL);

    // Log data being sent to PayPal for debugging
    error_log("PayPal data: " . print_r($padata, true));	
		
	//We need to execute the "SetExpressCheckOut" method to obtain paypal token
	$httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, 
	                                   $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
	//Respond according to message we receive from Paypal
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {					
		if($PayPalMode=='sandbox')
			$paypalmode = '.sandbox';
		else
			$paypalmode = '';
				
		//Redirect user to PayPal store with Token received.
		$paypalurl ='https://www'.$paypalmode. 
		            '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.
					$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	}
	else {
		//Show error message
		echo "<div style='color:red'><b>SetExpressCheckOut failed : </b>".
		      urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."</div>";
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"])) 
{	
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	$paypal_data = '';
	
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array
	foreach (array_values($_SESSION['Items']) as $key => $item)
	{
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	 // Recalculate final total to ensure accuracy
	 $finalTotal = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];

	//Data to be sent to PayPal
	$padata = '&TOKEN='.urlencode($token).
			  '&PAYERID='.urlencode($playerid).
			  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
			  $paypal_data.	
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]).
			  '&PAYMENTREQUEST_0_AMT=' . urlencode($finalTotal) .
              '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($_SESSION["ShipCharge"]) .
              '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($_SESSION["Tax"]) .
              '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode);
	
	//We need to execute the "DoExpressCheckoutPayment" at this point 
	//to receive payment from user.
	$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata, 
	                                   $PayPalApiUsername, $PayPalApiPassword, 
									   $PayPalApiSignature, $PayPalMode);
	
	//Check if everything went ok..
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	   // Assuming $httpParsedResponseAr contains the response from PayPal
		if ("SUCCESS" != strtoupper($httpParsedResponseAr["ACK"]) && 
		"SUCCESSWITHWARNING" != strtoupper($httpParsedResponseAr["ACK"])) {
		// Check for specific error codes and handle them
		if ($httpParsedResponseAr["L_ERRORCODE0"] == "10413") {
			// Totals mismatch error
			echo "<div style='color:red; margin-top: 20px;'>";
			echo "<strong>Error:</strong> There was a problem with your order totals. ";
			echo "The item totals do not match the total order amount as calculated. ";
			echo "Please review your shopping cart for accuracy and ensure that all totals are correct. ";
			echo "This may involve recalculating taxes, shipping, or discounts applied to your order.";
			echo "</div>";
		} else {
			// General error handling
			echo "<div style='color:red'><b>Error:</b> ";
			echo urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
			echo "</div>";
		}
		echo "<pre>" . print_r($httpParsedResponseAr, true) . "</pre>"; // Debug information
		exit;
		}
	else{
		// Update stock inventory in product table 
		//                after successful checkout
		$qry = "SELECT * FROM shopcartitem where ShopCartID = ?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i",$_SESSION["Cart"]);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0 ){ //select product in the shop cart
			//reduce the stock quantity in respective record in the product table by the quantity purchased
			while ($row = $result->fetch_array()) {
				$qry = "UPDATE product SET Quantity = Quantity - ? WHERE ProductID = ?";
				$stmt = $conn->prepare($qry);
				$stmt->bind_param("ii",$row["Quantity"],$row["ProductID"]);
				$stmt->execute();
				$stmt->close();
			}
		}
	
		// Update shopcart table, close the shopping cart (OrderPlaced=1)
		$total = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];
		$qry = "UPDATE shopcart SET OrderPlaced=1, Quantity=?, 
				SubTotal=?, ShipCharge=?, Tax=?, Total=? 
				WHERE ShopCartID=?";
		$stmt = $conn->prepare($qry);
		// "i" - integer, "d" - double
		$stmt->bind_param("iddddi", $_SESSION["NumCartItem"], 
						$_SESSION["SubTotal"], $_SESSION["ShipCharge"], 
						$_SESSION["Tax"], $total, 
						$_SESSION["Cart"]);
		$stmt->execute();
		$stmt->close();
		
		//We need to execute the "GetTransactionDetails" API Call at this point 
		//to get customer details
		$transactionID = urlencode(
		                 $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
		$nvpStr = "&TRANSACTIONID=".$transactionID;
		$httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
		                                   $PayPalApiUsername, $PayPalApiPassword, 
										   $PayPalApiSignature, $PayPalMode);

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
		   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
		   {
			//gennerate order entry and feed back orderID information
			//You may have more information for the generated order entry 
			//if you set those information in the PayPal test accounts.
			
			$ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"]));
			
			$ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
			if (isset($httpParsedResponseAr["SHIPTOSTREET2"]))
				$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
			if (isset($httpParsedResponseAr["SHIPTOCITY"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCITY"]);
			if (isset($httpParsedResponseAr["SHIPTOSTATE"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
			$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]). 
			                ' '.urldecode($httpParsedResponseAr["SHIPTOZIP"]);
				
			$ShipCountry = urldecode(
			               $httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
			
			$ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);			
			
			// 	Insert an Order record with shipping information
			//  Get the Order ID and save it in session variable.
			$qry = "INSERT INTO orderdata (ShipName, ShipAddress, ShipCountry, 
											ShipEmail, ShopCartID) 
					VALUES (?, ?, ?, ?, ?)";
			$stmt = $conn->prepare($qry);
			// "i" - integer, "s" - string
			$stmt->bind_param("ssssi", $ShipName, $ShipAddress, $ShipCountry, 
							$ShipEmail, $_SESSION["Cart"]);
			$stmt->execute();
			$stmt->close();
			$qry = "SELECT LAST_INSERT_ID() AS OrderID";
			$result = $conn->query($qry);
			$row = $result->fetch_array();
			$_SESSION["OrderID"] = $row["OrderID"];
				
			$conn->close();
				  
			// Clear cart items from the session
			unset($_SESSION['Items']);

			// Reset the "Number of Items in Cart" session variable to zero.
			$_SESSION["NumCartItem"] = 0;
	  		
			// Clear the session variable that contains Shopping Cart ID.
			unset($_SESSION["Cart"]);
			
			// Redirect shopper to the order confirmed page.
			header("Location: checkout.php");
			exit;

		} 
		else 
		{
		    echo "<div style='color:red'><b>GetTransactionDetails failed:</b>".
			                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
			$conn->close();
		}
	}
	else {
		echo "<div style='color:red'><b>DoExpressCheckoutPayment failed : </b>".
		                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

include("footer.php"); // Include the Page Layout footer
?>