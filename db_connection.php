<?php
$servername = "localhost:3307";
$username = "root";
$password = ""; // Replace with your actual password
$dbname = "fb_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed:");
}
?>