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

include("db_config.php");
$qry = "INSERT INTO Shopper (Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered)
        VALUES(?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($qry);

$stmt->bind_param("sssssssssis", $name, $BirthDate, $address, $country, $phone, $email, $password, $pwdquestion, $pwdanswer, $activestatus, $dateentered);
if ($stmt->execute())
{
    $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
    $result = $conn->query($qry);
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
include("header.php");
echo $Message;
include("footer.php");
?>