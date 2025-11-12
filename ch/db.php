<?php
// db.php – MySQL database connection
$host = "localhost";
$dbname = "asx";
$username = "root";
$password = ""; // use your actual MySQL password if any

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
