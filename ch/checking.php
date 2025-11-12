<?php
include("db.php");
include("auth.php");
require_role('clerk'); // Only clerks can access check-in

date_default_timezone_set('Asia/Colombo');

$errors = [];
$success = "";

// Form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $vip        = isset($_POST['vip']) ? 1 : 0;
    $room_id    = $_POST['room_id'];
    $checkin    = $_POST['checkin'];
    $checkout   = $_POST['checkout'];
    $card       = trim($_POST['card']);
    $is_walkin  = isset($_POST['walkin']) ? 1 : 0;

    $now = new DateTime(); // current system time
    $cutoff = new DateTime("19:00:00"); // 7PM

    // Hidden Rule: No reservation before 7PM without credit card
    if (!$is_walkin && $now < $cutoff && empty($card)) {
        $errors[] = "A credit card number is required for all reservations made before 7:00 PM (except walk-ins).";
    }

    // Insert guest and reservation if no errors
    if (empty($errors)) {
        // Insert guest
        $stmt = $conn->prepare("INSERT INTO guests (full_name, email, phone, is_vip) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $email, $phone, $vip);
        $stmt->execute();
        $guest_id = $stmt->insert_id;

        // Insert reservation
        $stmt2 = $conn->prepare("INSERT INTO reservations (guest_id, room_id, checkin_date, checkout_date, reservation_time, credit_card_number, is_walkin) VALUES (?, ?, ?, ?, NOW(), ?, ?)");
        $stmt2->bind_param("iisssi", $guest_id, $room_id, $checkin, $checkout, $card, $is_walkin);
        $stmt2->execute();

        // Update room status
        $conn->query("UPDATE rooms SET status = 'reserved' WHERE room_id = $room_id");

        $success = "Guest successfully checked in.";
    }
}

// Fetch available rooms
$rooms = $conn->query("SELECT * FROM rooms WHERE status = 'available'");
?>

<?php include("header.php"); ?>

<div class="container mt-5">
  <h2 class="mb-4">Guest Check-In</h2>

  <?php if ($errors): ?>
    <div class="alert alert-danger"><?php echo implode("<br>", $errors); ?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php endif; ?>

  <form method="POST" class="bg-light p-4 shadow rounded">
    <div class="mb-3">
      <label>Full Name</label>
      <input type="text" name="name" class="form-control" required />
    </div>

    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required />
    </div>

    <div class="mb-3">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" required />
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" name="vip" class="form-check-input" id="vip">
      <label class="form-check-label" for="vip">VIP Guest</label>
    </div>

    <div class="mb-3">
      <label>Available Room</label>
      <select name="room_id" class="form-select" required>
        <option value="">-- Select Room --</option>
        <?php while($room = $rooms->fetch_assoc()): ?>
          <option value="<?php echo $room['room_id']; ?>">
            Room <?php echo $room['room_number']; ?> - <?php echo $room['room_type']; ?> (LKR <?php echo $room['rate']; ?>)
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Check-in Date</label>
      <input type="date" name="checkin" class="form-control" required />
    </div>

    <div class="mb-3">
      <label>Check-out Date</label>
      <input type="date" name="checkout" class="form-control" required />
    </div>

    <div class="mb-3">
      <label>Credit Card Number</label>
      <input type="text" name="card" class="form-control" placeholder="Required if before 7PM" />
    </div>

    <div class="form-check mb-4">
      <input type="checkbox" name="walkin" class="form-check-input" id="walkin">
      <label class="form-check-label" for="walkin">Walk-in Guest</label>
    </div>

    <button type="submit" class="btn btn-primary w-100">Check-In Guest</button>
  </form>
</div>

<?php include("footer.php"); ?>
