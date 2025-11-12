<?php
// Use absolute path or correct relative path
$db_path = __DIR__ . '/../db.php';
if (file_exists($db_path)) {
    include $db_path;
} else {
    die("Database configuration file not found!");
}

// Check if connection exists and is valid
if (!isset($conn) || !$conn) {
    die("Database connection not established");
}

// Get the logged-in user's email from session
if (!isset($_SESSION['email'])) {
    die("User not logged in");
}
$user_email = $_SESSION['email'];

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build SQL query based on filter
switch ($filter) {
    case 'upcoming':
        $today = date('Y-m-d');
        $sql = "SELECT * FROM reservations WHERE email = ? AND (status = 'booked' OR status = 'confirmed') AND check_in >= '$today' ORDER BY created_at DESC";
        break;
    case 'completed':
        $sql = "SELECT * FROM reservations WHERE email = ? AND status = 'completed' ORDER BY created_at DESC";
        break;
    case 'cancelled':
        $sql = "SELECT * FROM reservations WHERE email = ? AND status = 'cancelled' ORDER BY created_at DESC";
        break;
    case 'all':
    default:
        $sql = "SELECT * FROM reservations WHERE email = ? ORDER BY created_at DESC";
        break;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<div class="container">
    <h2 class="section-title"><i class="fa-solid fa-clipboard-list"></i> My Reservation Records</h2>

    <table class="reservations-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Room Type</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Booked At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $statusClass = strtolower(str_replace(' ', '_', $row['status'])); ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['guest_name']) ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['room_type']) ?></td>
                        <td><?= date('M j, Y', strtotime($row['check_in'])) ?></td>
                        <td><?= date('M j, Y', strtotime($row['check_out'])) ?></td>
                        <td><?= htmlspecialchars($row['payment_method']) ?></td>
                        <td class="status-<?= $statusClass ?>"><?= ucfirst($row['status']) ?></td>
                        <td><?= date('M j, Y H:i', strtotime($row['created_at'])) ?></td>
                        <td>
                            <?php if ($row['status'] == 'booked' || $row['status'] == 'confirmed'): ?>
                                <a href="update_reservation.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                <form method="POST" action="cancel_reservation.php" onsubmit="return confirm('Are you sure you want to cancel this reservation?');" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                </form>
                            <?php elseif ($row['status'] == 'completed'): ?>
                                <button class="btn btn-info btn-sm">Review</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No reservations found for your account.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// Close connection when done
$stmt->close();
$conn->close();
?>