<?php
include("db.php");
include("auth.php");
require_role('clerk');
date_default_timezone_set('Asia/Colombo');

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'];
    $payment_method = $_POST['payment_method'];

    // Fetch reservation details
    $res = $conn->query("SELECT checkout_date, room_id FROM reservations WHERE reservation_id = $reservation_id");
    $row = $res->fetch_assoc();
    $scheduled_checkout = new DateTime($row['checkout_date']);
    $checkout_time = new DateTime();

    $late_fee = 0;
    if ($checkout_time > $scheduled_checkout) {
        $late_fee = 50.00;
    }

    // Calculate total
    $room_q = $conn->query("SELECT r.rate FROM reservations res JOIN rooms r ON res.room_id = r.room_id WHERE res.reservation_id = $reservation_id");
    $room_rate = $room_q->fetch_assoc()['rate'];

    $services_q = $conn->query("SELECT SUM(cost) as total_services FROM services WHERE reservation_id = $reservation_id");
    $service_total = $services_q->fetch_assoc()['total_services'] ?? 0;

    $total = $room_rate + $service_total + $late_fee;

    // Finalize checkout
    $conn->query("UPDATE reservations SET status='checked_out' WHERE reservation_id = $reservation_id");
    $conn->query("UPDATE rooms SET status='available' WHERE room_id = {$row['room_id']}");

    $success = "Checkout completed. Total: LKR " . number_format($total, 2);
}

$guests = $conn->query("SELECT res.reservation_id, g.full_name, r.room_number 
                        FROM reservations res 
                        JOIN guests g ON res.guest_id = g.guest_id 
                        JOIN rooms r ON res.room_id = r.room_id 
                        WHERE res.status = 'reserved'");
?>

<?php include("header.php"); ?>
<div class="container mt-5">
  <h2 class="mb-4">Guest Checkout</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="POST" class="bg-light p-4 shadow rounded">
    <div class="mb-3">
      <label>Select Guest</label>
      <select name="reservation_id" class="form-select" required>
        <option value="">-- Select a guest --</option>
        <?php while ($row = $guests->fetch_assoc()): ?>
          <option value="<?php echo $row['reservation_id']; ?>">
            <?php echo $row['full_name'] . " (Room " . $row['room_number'] . ")"; ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Payment Method</label>
      <select name="payment_method" class="form-select" required>
        <option value="cash">Cash</option>
        <option value="credit_card">Credit Card</option>
        <option value="split">Split Payment</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success w-100">Finalize Checkout</button>
  </form>
</div>
<?php include("footer.php"); ?>
