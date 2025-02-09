<?php

$name = $_POST["name"];
$birthdate = $_POST["birthdate"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = "(65) " . $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$pwdquestion = $_POST["pwdquestion"];
$pwdanswer = $_POST["pwdanswer"];
$activestatus = 1;
$dateentered = date("Y-m-d") . date("h:i:sa");

include_once("db_config.php");
$email_count = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $query = "SELECT COUNT(*) FROM Shopper WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($email_count);
    $stmt->fetch();
    $stmt->close();
}

if ($email_count == 0) {
    $query = "INSERT INTO Shopper (Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);

    $stmt->bind_param("sssssssssis", $name, $birthdate, $address, $country, $phone, $email, $password, $pwdquestion, $pwdanswer, $activestatus, $dateentered);
    if ($stmt->execute()) {
        $query = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($query);
        while ($row = $result->fetch_array()) {
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
        $Message = "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>
                    <h3 style='color:green; font-size: 1.5rem;'>Registration Successful!</h3>
                    </div>";
        $_SESSION["ShopperName"] = $name;
    } else {
        $Message = "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>
                    <h3 style='color:red; font-size: 1.5rem;'>Error in inserting record</h3>
                    </div>";
    }
    $stmt->close();
    $conn->close();
} else {
    $Message = "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>
                    <h3 style='color:red; font-size: 1.5rem;'>Email is already being used by another user</h3>
                    </div>";
}
include("header.php");
echo $Message;
include("footer.php");
