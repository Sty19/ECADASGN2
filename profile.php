<?php 
include_once("header.php"); 
include_once("db_config.php");

$name = "";
$birthdate = "";
$address = "";
$country = "";
$phone = "";
$currentemail = "";
$email = "";
$password = "";
$pwdquestion = "";
$pwdanswer = "";
$activestatus = "";
$dateentered = "";

$ShopperID = $_SESSION["ShopperID"];

$query = "SELECT Name, Birthdate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered FROM Shopper WHERE ShopperID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $ShopperID);
$stmt->execute();
$stmt->bind_result($name, $birthdate, $address, $country, $phone, $email, $password, $pwdquestion, $pwdanswer, $activestatus, $dateentered);
$stmt->fetch();
$stmt->close();

$currentemail = $email;
?>
<script type="text/javascript">

function validateForm()
{
    if (document.register.phone.value != ""){
        var str = document.register.phone.value;
        if (str.length != 8)
        {
            alert("Please enter an 8-digit phone number.");
            return false;
        }
        if (str.charAt(0) !== "6" && str.charAt(0) !== "8" && str.charAt(0) !== "9") {
            alert("Phone number in Singapore should start with 6, 8, or 9.");
            return false;
        }
    }
    return true;
}   
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta ProductTitle="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BabyJoy Store</title>
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/profile.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
</head>
<body>
<div class="profile-header">
    <h2 class="profile-title">Edit Profile</h2>
</div>
<div class="container">
    <form name="register" action="editProfile.php" method="post" onsubmit="return validateForm()">
        <div class="mb-3 row">
            <label class="col-form-label" for="name">Name:</label>
            <div>
                <input class="form-control" name="name" id="name" value="<?= htmlspecialchars($name) ?>" type="text" required /> 
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-form-label" for="birthdate">Birth Date:</label>
            <div>
                <input class="form-control" name="birthdate" id="birthdate" type="date" value="<?= htmlspecialchars($birthdate) ?>"  required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-form-label" for="address">Address:</label>
            <div>
                <textarea class="form-control" name="address" id="address" cols="25" rows="4" required ><?= htmlspecialchars($address) ?></textarea>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-form-label" for="country">Country:</label>
            <div>
                <input class="form-control" name="country" id="country" type="text" value="<?= htmlspecialchars($country) ?>"  required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-form-label" for="phone">Phone:</label>
            <div>
                <input class="form-control" name="phone" id="phone" type="text" value="<?= htmlspecialchars(substr($phone, 5)) ?>"  required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-form-label" for="email">Email Address:</label>
            <div>
                <input class="form-control" name="email" id="email" type="email" required value="<?= htmlspecialchars($email) ?>"  required /> 
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-form-label" for="password">Password:</label>
            <div>
                <input class="form-control" name="password" id="password" type="password" required value="<?= htmlspecialchars($password) ?>"  required /> 
            </div>
        </div>
        <p>If you forget your password, this question and answer will help allow you to retrieve your password.</p>
        <div class="mb-3 row">
            <label class="col-form-label" for="pwdquestion">Password Question:</label>
            <div>
                <input class="form-control" name="pwdquestion" id="pwdquestion" type="text" required value="<?= htmlspecialchars($pwdquestion) ?>"  required /> 
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-form-label" for="pwdanswer">Password Answer:</label>
            <div>
                <input class="form-control" name="pwdanswer" id="pwdanswer" type="text" required value="<?= htmlspecialchars($pwdanswer) ?>"  required /> 
            </div>
        </div>
        <input class="form-control" name="activestatus" id="activestatus" type="hidden" required value="<?= htmlspecialchars($activestatus) ?>" />
        <input class="form-control" name="dateentered" id="dateentered" type="hidden" required value="<?= htmlspecialchars($dateentered) ?>" />
        <input class="form-control" name="currentemail" id="currentemail" type="hidden" required value="<?= htmlspecialchars($currentemail) ?>" />
        <div class="mb-3 row">
            <div>
                <button type="submit">Save Edit</button>
            </div>
        </div>
    </form>
</div>
<?php 
include("footer.php"); 
?>
</body>
</html>
