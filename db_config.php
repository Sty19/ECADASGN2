<?php
$host = "localhost";
$user = "root";  // Default XAMPP MySQL username
$pass = "";      // Leave blank if no password
$dbname = "ecadasgn1";  // Change to match your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding (optional)
$conn->set_charset("utf8");
?>
