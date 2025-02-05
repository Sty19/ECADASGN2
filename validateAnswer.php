<?php
include_once("db_config.php");

$email = $_POST["email"];
$pwdanswer = $_POST["pwdanswer"];

$query = "SELECT PwdAnswer, Password FROM Shopper WHERE Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($correct_answer, $password);
$stmt->fetch();
$stmt->close();

if (strtolower(trim($pwdanswer)) === strtolower(trim($correct_answer))) {
    echo "Your password is: <b>" . htmlspecialchars($password) . "</b>";
} else {
    echo "Incorrect answer. Please try again.";
}

$conn->close();
?>
