<?php
$servername = "localhost";
$username = "digitech";
$password = "successful badger";
$dbname = "2024nathanangeles";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


?>
