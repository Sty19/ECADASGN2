<?php
session_start();

$name = $_POST["name"];
$birthdate = $_POST["birthdate"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = "(65) ".$_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$pwdquestion = $_POST["pwdquestion"];
$pwdanswer = $_POST["pwdanswer"];
$activestatus = 1;
$dateentered = date("Y-m-d").date("h:i:sa");

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

if ($email_count == 0){
    $query = "INSERT INTO Shopper (Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);

    $stmt->bind_param("sssssssssis", $name, $BirthDate, $address, $country, $phone, $email, $password, $pwdquestion, $pwdanswer, $activestatus, $dateentered);
    if ($stmt->execute())
    {
        $query = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($query);
        while($row = $result->fetch_array())
        {
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
        $Message = "Registration Successful! <br/>";
        $_SESSION["ShopperName"] = $name;
    }
    else{
        $Message = "<h3 style='color:red'>Error in inserting record</h3>";
    }
    $stmt->close();
    $conn->close();
    
} else {
    $Message = "<h3 style='color:red'>Email is already being used by another user</h3>";
}
include("header.php");
echo $Message;
include("footer.php");
?>