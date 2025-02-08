<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta ProductTitle="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BabyJoy Store</title>
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/styles.css">
    <link rel="stylesheet" type="text/css" href="ECAD2024Oct_Assignment_1_Input_Files/css/login.css">

    <!-- Box Icons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

</head>
<body>
<header>
    <?php include 'header.php'; ?>
</header>

<div class="login-container">
    <form action="checkLogin.php" method="post">
        <div class="login-row">
            <div class="col-sm-9 offset-sm-3">
                <span class="login-title">Member Login</span>
            </div>
        </div>

        <div class="login-row">
            <label class="login-label" for="email"> Email Address: </label>
            <div class="col-sm-9">
                <input class="login-input" type="email" name="email" id="email" required />
            </div>
        </div>

        <div class="login-row">
            <label class="login-label" for="password"> Password: </label>
            <div class="col-sm-9">
                <input class="login-input" type="password" name="password" id="password" required />
            </div>
        </div>

        <div class='login-row'>
            <div class='col-sm-9 offset-sm-3'>
                <button type='submit' class="login-btn">Login</button>
                <div class="login-links">
                    <p>Please sign up if you do not have an account.</p>
                    <p><a href="forgetPassword.php">Forget Password</a></p>
                    <p><a href="signup.php">Signup For an Account</a></p>
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>