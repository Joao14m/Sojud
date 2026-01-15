<?php
session_start();

$server = "localhost";
$dbname = "soccer";
$dbusername = "root"; 
$dbpassword = "graderpassword";

$conn = new mysqli($server, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
} 
?>
