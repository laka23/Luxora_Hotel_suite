<?php
session_start();
include 'db.php';

// CSRF token generation and validation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid reservation ID.");
}

$id = intval($_GET['id']);
$message = "";

// Debug: log the reservation ID
error_log("Update Reservation: ID = " . $id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    $guest_name = trim($_POST['guest_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $room_type = trim($_POST['room_type']);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $status = $_POST['status'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Invalid email format.</div>";
    }
    // Validate date logic
    elseif (strtotime($check_out) <= strtotime($check_in)) {
        $message = "<div class='alert alert-danger'>Check-out date must be after check-in date.</div>";
    } else {
        // Debug: log status value before update
        error_log("Update Reservation: Status to update = " . $status);

        // Prepared statement
        $stmt = $conn->prepare("UPDATE reservations SET guest_name=?, email=?, phone=?, room_type=?, check_in=?, check_out=?, `status`=? WHERE id=?");
        if ($stmt === false) {
            error_log("Prepare failed: " . $conn->error);
            die("Database error: failed to prepare update statement.");
        }
        $stmt->bind_param("sssssssi", $guest_name, $email, $phone, $room_type, $check_in, $check_out, $status, $id);

        if ($stmt->execute()) {
            // Debug: log successful update
            error_log("Update Reservation: Update executed successfully for ID " . $id);

            // Redirect after successful update to avoid resubmission
            $_SESSION['success_message'] = "Reservation updated successfully.";
            header("Location: login/MyBookings.php");
            exit();
        } else {
            // Debug: log update error
            error_log("Update Reservation: Update error - " . $stmt->error);

            $message = "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }

        $stmt->close();
    }
}

// Fetch reservation data
$stmt = $conn->prepare("SELECT id, guest_name, email, phone, room_type, check_in, check_out, `status` FROM reservations WHERE id = ?");
if ($stmt === false) {
    error_log("Prepare failed: " . $conn->error);
    die("Database error: failed to prepare statement.");
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Debug: log number of rows found
error_log("Update Reservation: Rows found = " . $result->num_rows);

if ($result->num_rows !== 1) {
    die("Reservation not found.");
}

$row = $result->fetch_assoc();

// Debug: log the fetched row keys and values
error_log("Update Reservation: Fetched row data: " . print_r($row, true));

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background: #f0f4f8; font-family: 'Poppins', sans-serif;">
<div class="container mt-5 bg-white p-4 rounded shadow">
    <h2 class="mb-4 text-center text-primary">Update Reservation</h2>

    <?= $message ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <div class="form-group">
            <label>Guest Name:</label>
            <input type="text" name="guest_name" class="form-control" value="<?= htmlspecialchars($row['guest_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>" required>
        </div>
        <?php
        // Use fixed room types as requested by user
        $roomTypes = [
            'single room',
            'double room',
            'deluxr room',
            'luxury room',
            'executiue room',
            'suite',
            'family room',
            'honeymon suite',
            'presidential suite',
            'penthouse suite'
        ];
        ?>
        <div class="form-group">
            <label>Room Type:</label>
            <select name="room_type" class="form-control" required>
                <?php foreach ($roomTypes as $type): ?>
                    <option value="<?= htmlspecialchars($type) ?>" <?= $row['room_type'] == $type ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Check-in Date:</label>
            <input type="date" name="check_in" class="form-control" value="<?= htmlspecialchars($row['check_in']) ?>" required>
        </div>
        <div class="form-group">
            <label>Check-out Date:</label>
            <input type="date" name="check_out" class="form-control" value="<?= htmlspecialchars($row['check_out']) ?>" required>
        </div>
        <div class="form-group">
            <label>Status:</label>
            <select name="status" class="form-control" required>
                <option value="reserved" <?= $row['status'] == 'reserved' ? 'selected' : '' ?>>Reserved</option>
                <option value="checked_in" <?= $row['status'] == 'checked_in' ? 'selected' : '' ?>>Checked In</option>
                <option value="checked_out" <?= $row['status'] == 'checked_out' ? 'selected' : '' ?>>Checked Out</option>
                <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                <option value="no_show" <?= $row['status'] == 'no_show' ? 'selected' : '' ?>>No Show</option>
                <option value="late_checkout" <?= $row['status'] == 'late_checkout' ? 'selected' : '' ?>>Late Checkout</option>
            </select>
        </div>
        <div class="form-group">
            <label>Notes:</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="login/MyBookings.php" class="btn btn-secondary">Back</a>
    </form>
</div>

</body>
</html>
