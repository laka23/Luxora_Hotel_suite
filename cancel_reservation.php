<?php
include 'db.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $reservation_id = intval($_POST['id']);

    // Sanity check (optional: check status first to prevent canceling checked-in/out)
    $check = mysqli_query($conn, "SELECT * FROM reservations WHERE id = $reservation_id");
    if (mysqli_num_rows($check) == 0) {
        // Reservation not found
        header("Location: reservations.php?error=Reservation not found.");
        exit;
    }

    // Cancel the reservation (change status instead of deleting)
    $update = mysqli_query($conn, "UPDATE reservations SET status = 'Cancelled' WHERE id = $reservation_id");

    if ($update) {
        header("Location: MyBookings.php?success=Reservation cancelled successfully.");
    } else {
        header("Location: MyBookings.php?error=Failed to cancel reservation.");
    }
    exit;
} else {
    // Invalid request
    header("Location: MyBookings.php?error=Invalid request.");
    exit;
}
?>
