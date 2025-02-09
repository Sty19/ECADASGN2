<?php
include("header.php");
include_once("db_config.php");


$question = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $email = $_POST["email"];
    $query = "SELECT PwdQuestion FROM Shopper WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($question);
    $stmt->fetch();
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta ProductTitle="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BabyJoy Store</title>
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/forgetpassword.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

</head>
<body>

<div style="width:80%; margin:auto;">
<form name="getQuestion" action="" method="post">
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="email">Account Email: </label>
        <div class="col-sm-9">
            <input class="form-control" name="email" id="email" type="email" required value="<?= htmlspecialchars($email) ?>" />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="email"></label>
        <div class="col-sm-9">
            <button type="submit">Get Password Question</button>
        </div>
    </div>
</form>

<?php if ($question): ?>
    <form name="getPassword" action="validateAnswer.php" method="post">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Password Question:</label>
            <div class="col-sm-9">
                <input class="form-control" type="text" value="<?= htmlspecialchars($question) ?>" readonly>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label" for="pwdanswer">Password Answer:</label>
            <div class="col-sm-9">
                <input class="form-control" name="pwdanswer" id="pwdanswer" type="text" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <button type="submit">Retrieve Password</button>
            </div>
        </div>
    </form>
<?php endif; ?>

</div>
<?php include("footer.php"); ?>
</body>
</html>
