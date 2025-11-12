<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: admin_rooms.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: admin_rooms.php?msg=Room+deleted+successfully");
    exit();
} else {
    $error = htmlspecialchars($stmt->error);
    $stmt->close();
    die("Error deleting room: $error");
}
?>
