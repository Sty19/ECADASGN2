<?php
$host = "localhost";
$user = "root"; 
$pass = "";
$dbname = "ecadasgn1"; 
$port = "3306";

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
