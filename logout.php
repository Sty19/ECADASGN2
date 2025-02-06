<?php
session_start();
if (! isset($_SESSION["Cart"])) {
    
}
session_destroy();
header("Location: index.php");
exit();
?>