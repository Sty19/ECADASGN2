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

<div style="width:80%; margin:auto;">
<form name="register" action="editProfile.php" method="post" 
      onsubmit="return validateForm()">
    <div class="mb-3 row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Membership Registration</span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="name">Name:</label>
        <div class="col-sm-9">
            <input class="form-control" name="name" id="name" value="<?= htmlspecialchars($name) ?>" 
                   type="text" required /> (required)
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="birthdate">Birth Date:</label>
        <div class="col-sm-9">
            <input class="form-control" name="birthdate" id="birthdate" 
                   type="date" value="<?= htmlspecialchars($birthdate) ?>" />
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="address">Address:</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="address" id="address"
                      cols="25" rows="4"><?= htmlspecialchars($address) ?></textarea>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="country">Country:</label>
        <div class="col-sm-9">
            <input class="form-control" name="country" id="country" type="text" value="<?= htmlspecialchars($country) ?>" />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="phone">Phone:</label>
        <div class="col-sm-9">
            <input class="form-control" name="phone" id="phone" type="text" value="<?= htmlspecialchars(substr($phone, 5)) ?>" />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="email">
            Email Address:</label>
        <div class="col-sm-9">
            <input class="form-control" name="email" id="email" 
                   type="email" required value="<?= htmlspecialchars($email) ?>" /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="password">
            Password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="password" id="password" 
                   type="password" required value="<?= htmlspecialchars($password) ?>" /> (required)
        </div>
    </div>

    <p>If you forget your password, this question and answer will help allow you to retrieve your password</p>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="pwdquestion">
            Password Question: </label>
        <div class="col-sm-9">
            <input class="form-control" name="pwdquestion" id="pwdquestion" 
                   type="text" required value="<?= htmlspecialchars($pwdquestion) ?>" /> (required)
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="pwdanswer">
            Password Answer: </label>
        <div class="col-sm-9">
            <input class="form-control" name="pwdanswer" id="pwdanswer" 
                   type="text" required value="<?= htmlspecialchars($pwdanswer) ?>" /> (required)
        </div>
    </div>
    <input class="form-control" name="activestatus" id="activestatus" type="hidden" required value="<?= htmlspecialchars($activestatus) ?>" />
    <input class="form-control" name="dateentered" id="dateentered" type="hidden" required value="<?= htmlspecialchars($dateentered) ?>" />
    <input class="form-control" name="currentemail" id="currentemail" type="hidden" required value="<?= htmlspecialchars($currentemail) ?>" />
    <div class="mb-3 row">       
        <div class="col-sm-9 offset-sm-3">
            <button type="submit">Save Edit</button>
        </div>
    </div>
</form>
</div>
<?php 
include("footer.php"); 
?>
