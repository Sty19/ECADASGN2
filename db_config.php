<?php
$host = "localhost";
$user = "root";  // Default XAMPP MySQL username
$pass = "";      // Leave blank if no password
$dbname = "ecadasgn1"; 
$port = "3304";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding (optional)
$conn->set_charset("utf8");
?>
