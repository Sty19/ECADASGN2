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
$activestatus = $_POST["activestatus"];
$dateentered = $_POST["dateentered"];
$currentemail = $_POST["currentemail"];

include_once("db_config.php");
$email_count = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && $email != $currentemail) {
    $query = "SELECT COUNT(*) FROM Shopper WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($email_count);
    $stmt->fetch();
    $stmt->close();
}
if ($email_count == 0) {
    $query = "UPDATE Shopper 
              SET Name = ?, BirthDate = ?, Address = ?, Country = ?, Phone = ?, Password = ?, PwdQuestion = ?, PwdAnswer = ?, ActiveStatus = ?, DateEntered = ?
              WHERE Email = ?";
    $stmt = $conn->prepare($query);

    $stmt->bind_param("ssssssssiss", $name, $birthdate, $address, $country, $phone, $password, $pwdquestion, $pwdanswer, $activestatus, $dateentered, $email);

    if ($stmt->execute()) {
        $query = "SELECT ShopperID FROM Shopper WHERE Email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
        
        $Message = "Profile Updated Successfully! <br/>";
        $_SESSION["ShopperName"] = $name;
    } else {
        $Message = "<h3 style='color:red'>Error in updating record</h3>";
    }

    $stmt->close();
    $conn->close();
} else {
    $Message = "<h3 style='color:red'>Email is already being used by another user</h3>";
}

include("header.php");
echo $Message;
include("footer.php");
