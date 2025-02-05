<?php
$password = "johndoe"; // The original password
$hashed_password = password_hash($password, PASSWORD_DEFAULT); 

echo "Original Password: $password <br>";
echo "Generated Hash: $hashed_password <br>";

if (password_verify($password, $hashed_password)) {
    echo "Password verification SUCCESS!";
} else {
    echo "Password verification FAILED!";
}
?>
