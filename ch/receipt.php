<!-- receipt.php -->
<?php
include("db.php");
include("header.php");
date_default_timezone_set('Asia/Colombo');

$reservation_id = $_GET['id'] ?? null;
$billing = null;
$late_fee = 0;
$services = [];

if ($reservation_id) {
    // Fetch all billing info
    $res = $conn->query("SELECT res.*, g.full_name, r.room_number, r.room_type, r.rate, res.checkin_date, res.checkout_date 
                         FROM reservations res 
                         JOIN guests g ON res.guest_id = g.guest_id 
                         JOIN rooms r ON res.room_id = r.room_id 
                         WHERE res.reservation_id = $reservation_id");

    if ($res->num_rows > 0) {
        $billing = $res->fetch_assoc();

        // Late fee check
        $checkout = new DateTime($billing['checkout_date']);
        $now = new DateTime();
        if ($now > $checkout) {
            $late_fee = 50.00;
        }

        // Service charges
        $srv = $conn->query("SELECT service_name, cost FROM services WHERE reservation_id = $reservation_id");
        while ($row = $srv->fetch_assoc()) {
            $services[] = $row;
        }
    }
}
?>

<div class="container mt-5" id="printArea">
  <div class="card shadow border-0">
    <div class="card-body p-5">
      <div class="text-center mb-4">
        <h2 class="text-uppercase">Hotel Prime</h2>
        <h5>Official Guest Receipt</h5>
      </div>

      <?php if ($billing): ?>
        <p><strong>Guest:</strong> <?php echo $billing['full_name']; ?></p>
        <p><strong>Room:</strong> <?php echo $billing['room_type'] . " - Room " . $billing['room_number']; ?></p>
        <p><strong>Check-in:</strong> <?php echo $billing['checkin_date']; ?> | 
           <strong>Check-out:</strong> <?php echo $billing['checkout_date']; ?></p>

        <hr>

        <h5 class="mb-3">Charges Breakdown:</h5>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between">
            Room Charge
            <span>LKR <?php echo number_format($billing['rate'], 2); ?></span>
          </li>
          <?php 
            $service_total = 0;
            if (!empty($services)): 
              foreach ($services as $s): 
                $service_total += $s['cost'];
          ?>
            <li class="list-group-item d-flex justify-content-between">
              <?php echo $s['service_name']; ?>
              <span>LKR <?php echo number_format($s['cost'], 2); ?></span>
            </li>
          <?php endforeach; endif; ?>

          <?php if ($late_fee > 0): ?>
            <li class="list-group-item d-flex justify-content-between">
              Late Checkout Fee
              <span>LKR <?php echo number_format($late_fee, 2); ?></span>
            </li>
          <?php endif; ?>
          
          <li class="list-group-item d-flex justify-content-between bg-light fw-bold">
            Total Amount
            <span>
              LKR <?php echo number_format($billing['rate'] + $service_total + $late_fee, 2); ?>
            </span>
          </li>
        </ul>

        <p class="mt-4 text-center text-muted">Thank you for staying with us. We hope to welcome you again!</p>
      <?php else: ?>
        <p class="text-danger">Invalid or missing reservation ID.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="text-center mt-3">
    <button class="btn btn-outline-primary" onclick="window.print()">Print Receipt</button>
  </div>
</div>

<?php include("footer.php"); ?>
