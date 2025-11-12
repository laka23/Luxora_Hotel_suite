<?php
// /reservations/auto_cancel_cron.php
include("db.php");

date_default_timezone_set('Asia/Colombo');
$currentTime = new DateTime();
$today = $currentTime->format('Y-m-d');

// Auto-cancel logic for non-credit card reservations
$sql = "UPDATE reservations 
        SET status = 'cancelled' 
        WHERE DATE(checkin_date) = ? 
          AND credit_card_number IS NULL 
          AND status = 'reserved'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);

if ($stmt->execute()) {
    echo "[Success] Cancelled unpaid reservations for $today\n";
} else {
    echo "[Error] Failed to cancel unpaid reservations.\n";
}
?>
