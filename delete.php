<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_db";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email'])) {
    die("Session not found. Please log in first.");
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $query = "DELETE FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        die("Failed to delete account: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Account</title>
</head>
<body>
    <h2>Are you sure you want to delete your account?</h2>
    <form method="POST">
        <button type="submit">Yes, Delete Account</button>
        <a href="index.html">Cancel</a>
    </form>
</body>
</html>
