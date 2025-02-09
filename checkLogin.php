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
    echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>
        <h3 style='color:red;'>Invalid Login Credentials</h3>
      </div>";

} else {
    echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>
        <h3 style='color:red;'>Invalid Login Credentials</h3>
      </div>";

}

$conn->close();
