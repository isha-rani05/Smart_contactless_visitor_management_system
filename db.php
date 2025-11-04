<?php
// db.php
// Set timezone so PHP date() matches your local time (change if needed)
date_default_timezone_set('Asia/Kolkata');

$servername = "localhost";
$username = "root";   // change if different
$password = "";       // change if different
$dbname = "visitor_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
