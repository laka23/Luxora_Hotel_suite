<?php
include("db.php");
include("auth.php");
require_role('clerk');

$res_id = $_GET['id'] ?? null;
if (!$res_id) {
    header("Location: checkout.php");
    exit();
}

// Fetch necessary info
$fetch = $conn->query("SELECT r.rate, res.room_id FROM reservations res JOIN rooms r ON res.room_id = r.room_id WHERE reservation_id = $res_id");
$row = $fetch->fetch_assoc();
$rate = $row['rate'];
$room_id = $row['room_id'];

$conn->query("UPDATE reservations SET status = 'checked_out' WHERE reservation_id = $res_id");
$conn->query("UPDATE rooms SET status = 'available' WHERE room_id = $room_id");

header("Location: receipt.php?id=$res_id");
exit();
?>
