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

<div style="width:80%; margin:auto;">
<form name="getQuestion" action="" method="post">
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="email">Account Email: </label>
        <div class="col-sm-9">
            <input class="form-control" name="email" id="email" type="email" required value="<?= htmlspecialchars($email) ?>" />
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
            <div class="col-sm-9 offset-sm-3">
                <button type="submit">Retrieve Password</button>
            </div>
        </div>
    </form>
<?php endif; ?>

</div>
<?php include("footer.php"); ?>
