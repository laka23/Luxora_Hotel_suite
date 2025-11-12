<?php
// /reservations/no_show_billing.php
include("db.php");

date_default_timezone_set('Asia/Colombo');
$today = (new DateTime())->format('Y-m-d');

// Find reservations for today that were not checked in
$sql = "SELECT reservation_id 
        FROM reservations 
        WHERE DATE(checkin_date) = ? 
          AND status = 'reserved' 
          AND credit_card_number IS NOT NULL";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $reservation_id = $row['reservation_id'];

    // Mark as no-show
    $conn->query("UPDATE reservations SET status = 'no_show' WHERE reservation_id = $reservation_id");

    // Optional: log billing event or create a billing record
    echo "[No-Show] Reservation #$reservation_id marked and charged.\n";
}
?>
