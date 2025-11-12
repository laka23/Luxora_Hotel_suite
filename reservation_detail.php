<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid reservation ID.";
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM reservations WHERE id = $id");

if (mysqli_num_rows($query) == 0) {
    echo "Reservation not found.";
    exit;
}

$reservation = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">

    <title>Reservation Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Reservation Details</h2>

    <table class="table table-bordered">
        <tr><th>ID</th><td><?= $reservation['id'] ?></td></tr>
        <tr><th>Customer Name</th><td><?= htmlspecialchars($reservation['customer_name']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($reservation['email']) ?></td></tr>
        <tr><th>Phone</th><td><?= htmlspecialchars($reservation['phone']) ?></td></tr>
        <tr><th>Room</th><td><?= htmlspecialchars($reservation['room_name']) ?></td></tr>
        <tr><th>Check-in Date</th><td><?= $reservation['checkin_date'] ?></td></tr>
        <tr><th>Check-out Date</th><td><?= $reservation['checkout_date'] ?></td></tr>
        <tr><th>Status</th><td><?= $reservation['status'] ?></td></tr>
        <tr><th>Notes</th><td><?= nl2br(htmlspecialchars($reservation['notes'])) ?></td></tr>
    </table>

    <a href="reservations.php" class="btn btn-secondary">Back to Reservations</a>
</div>
</body>
</html>
