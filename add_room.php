<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login/loging.html");
    exit();
}
include 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = trim($_POST['room_number']);
    $room_type = trim($_POST['room_type']);
    $beds = intval($_POST['beds']);
    $price = floatval($_POST['price']);
    $status = trim($_POST['status']);
    $image_path = trim($_POST['image_path']);

    // Basic validation
    if (empty($room_number) || empty($room_type) || $beds <= 0 || $price <= 0 || empty($status)) {
        $message = "<div class='alert alert-danger'>Please fill in all required fields correctly.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO rooms (room_number, room_type, beds, price, status, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssidss", $room_number, $room_type, $beds, $price, $status, $image_path);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Room added successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Room</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="login/Css/add_room.css">
</head>
<body>
<div class="container mt-5">
    <h2>Add New Room</h2>
    <?= $message ?>
    <form method="POST">
        <div class="mb-3">
            <label for="room_number" class="form-label">Room Number</label>
            <input type="text" class="form-control" id="room_number" name="room_number" required>
        </div>
        <div class="mb-3">
            <label for="room_type" class="form-label">Room Type</label>
            <select class="form-select" id="room_type" name="room_type" required>
                <option value="">Select a room type</option>
                <option value="Single Room">Single Room</option>
                <option value="Double Room">Double Room</option>
                <option value="Deluxe Room">Deluxe Room</option>
                <option value="Luxury Room">Luxury Room</option>
                <option value="Executive Room">Executive Room</option>
                <option value="Suite">Suite</option>
                <option value="Family Room">Family Room</option>
                <option value="Honeymoon Suite">Honeymoon Suite</option>
                <option value="Presidential Suite">Presidential Suite</option>
                <option value="Penthouse Suite">Penthouse Suite</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="beds" class="form-label">Number of Beds</label>
            <input type="number" class="form-control" id="beds" name="beds" min="1" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price ($)</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" min="0" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="available" selected>Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image_path" class="form-label">Image URL</label>
            <input type="text" class="form-control" id="image_path" name="image_path" placeholder="Optional">
        </div>
        <button type="submit" class="btn btn-success">Add Room</button>
        <a href="admin_rooms.php" class="btn btn-secondary">Back to Room Management</a>
    </form>
</div>
</body>
</html>
