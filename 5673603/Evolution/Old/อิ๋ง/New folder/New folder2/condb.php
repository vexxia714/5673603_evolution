<?php
$servername = "localhost";
$username = "root";
$password = "Cr!Z7qVNU*4hDHLM";
$dbname = "swcentric68db";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";
?>