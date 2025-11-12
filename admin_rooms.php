<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login/loging.html");
    exit();
}
include 'db.php'; // Your DB connection
$result = mysqli_query($conn, "SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Rooms</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="login/Css/admin_rooms.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Room Management</h2>
    <a href="login/MyBookings.php" class="btn btn-secondary mb-3">← Back to My Bookings</a>
    <a href="add_room.php" class="btn btn-success mb-3">+ Add New Room</a>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Room No</th>
                <th>Type</th>
                <th>Beds</th>
                <th>Price</th>
                <th>Status</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php $counter = 1; ?>
        <?php while ($room = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $counter ?></td>
                <td><?= htmlspecialchars($room['room_number']) ?></td>
                <td><?= htmlspecialchars($room['room_type']) ?></td>
                <td><?= $room['beds'] ?></td>
                <td>$<?= $room['price'] ?></td>
                <td><?= $room['status'] ?></td>
                <td>
                    <?php if ($room['image_path']): ?>
                        <img src="<?= $room['image_path'] ?>" width="80" alt="Room Image">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_room.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete_room.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this room?')">Delete</a>
                </td>
            </tr>
        <?php $counter++; endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
