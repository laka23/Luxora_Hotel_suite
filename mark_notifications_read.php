<?php
session_start();

if (!isset($_SESSION['email'])) {
    die(json_encode(['success' => false, 'message' => 'Not logged in']));
}

require_once 'db_connection.php'; // Your database connection file

$email = $_SESSION['email'];
$user_query = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Mark all notifications as read
$mark_read_query = "UPDATE notifications SET is_read = TRUE WHERE user_id = ?";
$stmt = $conn->prepare($mark_read_query);
$stmt->bind_param("i", $user_id);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
$conn->close();
?>