<!-- bill_generator.php -->
<?php
include("db.php");
include("header.php");
date_default_timezone_set('Asia/Colombo');

$billing_details = null;
$total_cost = 0;
$late_fee = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'];

    // Get guest, room, and reservation info
    $res = $conn->query("SELECT res.*, g.full_name, r.room_number, r.room_type, r.rate, res.checkout_date 
                         FROM reservations res 
                         JOIN guests g ON res.guest_id = g.guest_id 
                         JOIN rooms r ON res.room_id = r.room_id 
                         WHERE res.reservation_id = $reservation_id");

    if ($res->num_rows > 0) {
        $billing_details = $res->fetch_assoc();
        $room_cost = $billing_details['rate'];

        // Check for late checkout
        $checkout = new DateTime($billing_details['checkout_date']);
        $now = new DateTime();
        if ($now > $checkout) {
            $late_fee = 50.00; // flat fee for late checkout
        }

        // Fetch services
        $services = $conn->query("SELECT * FROM services WHERE reservation_id = $reservation_id");
        $service_total = 0;
        $service_items = [];
        while ($s = $services->fetch_assoc()) {
            $service_total += $s['cost'];
            $service_items[] = $s;
        }

        $total_cost = $room_cost + $service_total + $late_fee;
    }
}

// Get current reserved guests for dropdown
$reservations = $conn->query("SELECT res.reservation_id, g.full_name, r.room_number 
                              FROM reservations res 
                              JOIN guests g ON res.guest_id = g.guest_id 
                              JOIN rooms r ON res.room_id = r.room_id 
                              WHERE res.status = 'reserved'");
?>

<div class="container mt-5">
  <h2 class="mb-4">Bill Summary Preview</h2>

  <form method="POST" class="bg-light p-4 shadow rounded mb-4">
    <div class="mb-3">
      <label>Select Guest</label>
      <select name="reservation_id" class="form-select" required>
        <option value="">-- Select a guest --</option>
        <?php while ($row = $reservations->fetch_assoc()): ?>
          <option value="<?php echo $row['reservation_id']; ?>">
            <?php echo $row['full_name'] . " - Room " . $row['room_number']; ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary w-100">Generate Bill</button>
  </form>

  <?php if ($billing_details): ?>
    <div class="card shadow">
      <div class="card-header bg-dark text-white">
        <h4>Billing Summary - <?php echo $billing_details['full_name']; ?></h4>
      </div>
      <div class="card-body">
        <p><strong>Room:</strong> <?php echo $billing_details['room_type'] . " (" . $billing_details['room_number'] . ")"; ?></p>
        <p><strong>Room Cost:</strong> LKR <?php echo number_format($billing_details['rate'], 2); ?></p>

        <h5 class="mt-4">Additional Services:</h5>
        <ul class="list-group mb-3">
          <?php if (!empty($service_items)): ?>
            <?php foreach ($service_items as $item): ?>
              <li class="list-group-item d-flex justify-content-between">
                <?php echo $item['service_name']; ?>
                <span>LKR <?php echo number_format($item['cost'], 2); ?></span>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
              <li class="list-group-item">No extra services recorded.</li>
          <?php endif; ?>
        </ul>

        <?php if ($late_fee > 0): ?>
          <p><strong>Late Checkout Fee:</strong> LKR <?php echo number_format($late_fee, 2); ?></p>
        <?php endif; ?>

        <h4 class="text-end">Total: LKR <?php echo number_format($total_cost, 2); ?></h4>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
