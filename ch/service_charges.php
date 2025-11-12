<!-- service_charges.php -->
<?php
include("db.php");
include("header.php");
date_default_timezone_set('Asia/Colombo');

$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'];
    $service_name = $_POST['service_name'];
    $cost = floatval($_POST['cost']);

    if ($reservation_id && $service_name && $cost >= 0) {
        $stmt = $conn->prepare("INSERT INTO services (reservation_id, service_name, cost) VALUES (?, ?, ?)");
        $stmt->bind_param("isd", $reservation_id, $service_name, $cost);
        $stmt->execute();

        $success = "Service charge added successfully.";
    } else {
        $error = "All fields are required and cost must be non-negative.";
    }
}

// Fetch active reservations
$reservations = $conn->query("SELECT res.reservation_id, g.full_name, r.room_number 
                              FROM reservations res 
                              JOIN guests g ON res.guest_id = g.guest_id 
                              JOIN rooms r ON res.room_id = r.room_id 
                              WHERE res.status = 'reserved'");
?>

<div class="container mt-5">
  <h2 class="mb-4">Add Extra Service Charges</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="POST" class="bg-light p-4 shadow rounded">
    <div class="mb-3">
      <label>Select Guest</label>
      <select name="reservation_id" class="form-select" required>
        <option value="">-- Select a guest/reservation --</option>
        <?php while ($row = $reservations->fetch_assoc()): ?>
          <option value="<?php echo $row['reservation_id']; ?>">
            <?php echo $row['full_name'] . " - Room " . $row['room_number']; ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Service Name</label>
      <input type="text" name="service_name" class="form-control" placeholder="e.g., Laundry, Room Service" required>
    </div>

    <div class="mb-3">
      <label>Cost (LKR)</label>
      <input type="number" step="0.01" name="cost" class="form-control" placeholder="e.g., 1500.00" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Add Service</button>
  </form>
</div>

<?php include("footer.php"); ?>
