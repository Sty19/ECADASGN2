<?php 
include("header.php"); 
?>
<script type="text/javascript">

function validateForm()
{
	if (document.register.password.value != document.register.password2.value){
        alert("Passwords not matched!");
        return false;
    }

    if (document.register.phone.value != ""){
        var str = document.register.phone.value
        if (str.length != 8)
        {
            alert("Please enter a 8-digit phone number.")
            return false
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
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/signup.css">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

</head>
<body>
<div class="signup-container">
  <h2 class="signup-title">Membership Registration</h2>
  <form name="register" action="addmember.php" method="post" onsubmit="return validateForm()">
    <div class="form-group">
      <label class="form-label" for="name">Name:</label>
      <input class="form-input" name="name" id="name" type="text" required />
    </div>

    <div class="form-group">
      <label class="form-label" for="birthdate">Birth Date:</label>
      <input class="form-input" name="birthdate" id="birthdate" type="date" required />
    </div>

    <div class="form-group">
      <label class="form-label" for="address">Address:</label>
      <textarea class="form-textarea" name="address" id="address" rows="4" required ></textarea>
    </div>

    <div class="form-group">
      <label class="form-label" for="country">Country:</label>
      <input class="form-input" name="country" id="country" type="text"  required />
    </div>

    <div class="form-group">
      <label class="form-label" for="phone">Phone:</label>
      <input class="form-input" name="phone" id="phone" type="text"  required />
    </div>

    <div class="form-group">
      <label class="form-label" for="email">Email Address:</label>
      <input class="form-input" name="email" id="email" type="email" required />
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Password:</label>
      <input class="form-input" name="password" id="password" type="password" required />
    </div>

    <div class="form-group">
      <label class="form-label" for="password2">Retype Password:</label>
      <input class="form-input" name="password2" id="password2" type="password" required />
    </div>

    <div class="form-group">
      <label class="form-label" for="pwdquestion">Password Question:</label>
      <input class="form-input" name="pwdquestion" id="pwdquestion" type="text" required />
    </div>

    <div class="form-group">
      <label class="form-label" for="pwdanswer">Password Answer:</label>
      <input class="form-input" name="pwdanswer" id="pwdanswer" type="text" required />
    </div>
    <div class="form-group-button">
      <button class="signup-btn" type="submit">Register</button>
    </div>
  </form>
</div>

<?php 
include("footer.php"); 
?>
</body>
</html>