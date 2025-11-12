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
$message = "";

// Fetch room data
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
$stmt->close();

if (!$room) {
    header("Location: admin_rooms.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = trim($_POST['room_number']);
    $room_type = trim($_POST['room_type']);
    $beds = intval($_POST['beds']);
    $price = floatval($_POST['price']);
    $status = trim($_POST['status']);
    $image_path = trim($_POST['image_path']);

    if (empty($room_number) || empty($room_type) || $beds <= 0 || $price <= 0 || empty($status)) {
        $message = "<div class='alert alert-danger'>Please fill in all required fields correctly.</div>";
    } else {
        $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, room_type = ?, beds = ?, price = ?, status = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("ssidssi", $room_number, $room_type, $beds, $price, $status, $image_path, $id);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Room updated successfully.</div>";
            // Refresh room data
            $stmt->close();
            $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $room = $result->fetch_assoc();
            $stmt->close();
        } else {
            $message = "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Room</h2>
    <?= $message ?>
    <form method="POST">
        <div class="mb-3">
            <label for="room_number" class="form-label">Room Number</label>
            <input type="text" class="form-control" id="room_number" name="room_number" value="<?= htmlspecialchars($room['room_number']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="room_type" class="form-label">Room Type</label>
            <input type="text" class="form-control" id="room_type" name="room_type" value="<?= htmlspecialchars($room['room_type']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="beds" class="form-label">Number of Beds</label>
            <input type="number" class="form-control" id="beds" name="beds" min="1" value="<?= $room['beds'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price ($)</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" min="0" value="<?= $room['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="available" <?= $room['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="occupied" <?= $room['status'] === 'occupied' ? 'selected' : '' ?>>Occupied</option>
                <option value="maintenance" <?= $room['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image_path" class="form-label">Image URL</label>
            <input type="text" class="form-control" id="image_path" name="image_path" value="<?= htmlspecialchars($room['image_path']) ?>" placeholder="Optional">
        </div>
        <button type="submit" class="btn btn-primary">Update Room</button>
        <a href="admin_rooms.php" class="btn btn-secondary">Back to Room Management</a>
    </form>
</div>
</body>
</html>
