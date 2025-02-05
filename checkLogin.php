<?php
session_start();

$email = $_POST["email"];
$pwd = $_POST["password"];

include_once("db_config.php");
$sql = "SELECT ShopperID, Name, Password FROM Shopper WHERE Email=?";

$stmn = $conn->prepare($sql);
$stmn->bind_param("s", $email);
$stmn->execute();
$result = $stmn->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        if ($pwd == $row["Password"]) {
            $_SESSION["ShopperName"] = $row["Name"];
            $_SESSION["ShopperID"] = $row["ShopperID"];
            $conn->close();
            header("Location: index.php");
            exit;
        }
    }
    echo "<h3 style='color:red'>Invalid Login Credentials</h3>";
} else {
    echo "<h3 style='color:red'>Invalid Login Credentials</h3>";
}

$conn->close();
