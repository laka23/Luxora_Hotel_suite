<?php
include("db.php");

date_default_timezone_set('Asia/Colombo');
$now = new DateTime();
$today = $now->format('Y-m-d');

$sql = "SELECT reservation_id, checkout_date FROM reservations 
        WHERE status = 'reserved' AND checkout_date < '$today'";
$reservations = $conn->query($sql);

while ($row = $reservations->fetch_assoc()) {
    $res_id = $row['reservation_id'];

    // Apply late fee and keep reservation active
    $conn->query("UPDATE reservations SET status = 'late_checkout' WHERE reservation_id = $res_id");

    // Optional: Log or notify
    echo "Marked reservation $res_id as late checkout\n";
}
?>
