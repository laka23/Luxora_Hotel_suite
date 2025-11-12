<?php
include 'db.php'; // Database connection

date_default_timezone_set("Asia/Colombo"); // Set your timezone
$today = date('Y-m-d');

// 1. Automatically check out guests if today >= check_out
$checkout_query = "
    UPDATE reservations 
    SET status = 'Checked-out'
    WHERE status = 'Checked-in' AND check_out <= '$today'
";
mysqli_query($conn, $checkout_query);

// 2. Automatically mark no-shows for guests who didn't check in yesterday
$noshow_query = "
    UPDATE reservations 
    SET status = 'No-show' 
    WHERE status = 'Confirmed' AND check_in < '$today'
";
mysqli_query($conn, $noshow_query);

// 3. Free up rooms after check-out
$release_rooms_query = "
    UPDATE rooms 
    SET status = 'Available' 
    WHERE room_type IN (
        SELECT room_type FROM reservations 
        WHERE status = 'Checked-out' AND check_out <= '$today'
    )
";
mysqli_query($conn, $release_rooms_query);

// 4. Automatically cancel reservations with cash payment only at 7 p.m. daily
$cancel_cash_reservations_query = "
    UPDATE reservations
    SET status = 'Cancelled'
WHERE payment_method = 'Cash' AND status IN ('Confirmed', 'booked')
";
mysqli_query($conn, $cancel_cash_reservations_query);
$affected_rows = mysqli_affected_rows($conn);

if ($affected_rows > 0) {
    file_put_contents("cron_log.txt", "[" . date("Y-m-d H:i:s") . "] Auto-cancelled $affected_rows cash payment reservation(s) at 7 p.m.\n", FILE_APPEND);
} else {
    file_put_contents("cron_log.txt", "[" . date("Y-m-d H:i:s") . "] No cash payment reservations to cancel at 7 p.m.\n", FILE_APPEND);
}

// Optional: You can log this action
file_put_contents("cron_log.txt", "[" . date("Y-m-d H:i:s") . "] Daily cron tasks completed\n", FILE_APPEND);

echo "Daily tasks executed.\n";
?>
