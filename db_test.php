<?php
// Test database connection and query

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'hotel_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Database connection successful.\n";

// Test query to check if reservations table exists and fetch one row
$query = "SELECT * FROM reservations LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "Reservations table found. Sample row:\n";
    print_r($row);
} else {
    echo "Reservations table is empty or does not exist.\n";
}

mysqli_close($conn);
?>
