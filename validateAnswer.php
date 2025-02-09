<?php
include_once("header.php"); 
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

$conn->close();

if (strtolower(trim($pwdanswer)) === strtolower(trim($correct_answer))) {
    $message = "<div style='font-size: 1.5rem; padding: 20px; text-align: center;'>Your password is: <b>" . htmlspecialchars($password) . "</b></div>";
} else {
    $message = "<div style='font-size: 1.5rem; padding: 20px; text-align: center; color: red;'>Incorrect answer. Please try again.</div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
</head>
<body>
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: Arial, sans-serif;">
        <?= $message ?>
    </div>
</body>
</html>
