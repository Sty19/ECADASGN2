<?php
session_start();
include("header.php");

if (!isset($_SESSION['OrderID'])) {
    header("Location: index.php");
    exit;
}

echo "<div class='container' style='margin:50px auto; text-align:center'>";
echo "<h2>Order Confirmation</h2>";
echo "<div class='confirmation-box'>";
echo "<p><i class='fas fa-check-circle'></i> Payment Successful!</p>";
echo "<p>Order ID: #" . $_SESSION["OrderID"] . "</p>";
echo "<p>Total Paid: S$" . number_format($_SESSION["SubTotal"], 2) . "</p>";
echo "<p>Thank you for shopping with BabyJoy!</p>";
echo "<a href='index.php' class='btn btn-primary'>Continue Shopping</a>";
echo "</div>";
echo "</div>";

// Clear session data
unset($_SESSION["Cart"]);
unset($_SESSION["OrderID"]);
unset($_SESSION["SubTotal"]);

include("footer.php");
?>