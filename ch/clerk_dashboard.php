<?php
include("db.php");
include("auth.php");

// Debug: output session role to check authorization issue
if (isset($_SESSION['role'])) {
    error_log("User role in session: " . $_SESSION['role']);
} else {
    error_log("User role not set in session.");
}

// Temporary debug output of session variables for diagnosis
echo "<pre>Session Data:\n";
print_r($_SESSION);
echo "</pre>";

require_role('clerk');
?>

<?php include("header.php"); ?>

<div class="container mt-4">
  <h2 class="mb-4">Welcome, <?php echo $_SESSION['username']; ?> 👋</h2>
  <p class="text-muted">Here’s your front desk dashboard for managing today’s operations.</p>

  <div class="row g-4 mt-3">
    <!-- Check-In -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <img src="/assets/images/checkin.jpg" class="card-img-top" alt="Check-In">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-sign-in-alt me-2"></i>Check-In</h5>
          <p class="card-text">Handle reservations and walk-in guests efficiently.</p>
          <a href="/checkin/checkin.php" class="btn btn-primary w-100">Go to Check-In</a>
        </div>
      </div>
    </div>

    <!-- Check-Out -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <img src="/assets/images/checkout.jpg" class="card-img-top" alt="Check-Out">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-sign-out-alt me-2"></i>Check-Out</h5>
          <p class="card-text">Complete checkouts and preview billing summaries.</p>
          <a href="/checkout/checkout.php" class="btn btn-success w-100">Go to Check-Out</a>
        </div>
      </div>
    </div>

    <!-- Services -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <img src="/assets/images/services.jpg" class="card-img-top" alt="Services">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-concierge-bell me-2"></i>Services</h5>
          <p class="card-text">Add extra charges like laundry, room service, and more.</p>
          <a href="/billing/service_charges.php" class="btn btn-warning w-100">Add Services</a>
        </div>
      </div>
    </div>

    <!-- Billing -->
    <div class="col-md-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <img src="/assets/images/billing.jpg" class="card-img-top" alt="Billing">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-file-invoice-dollar me-2"></i>Billing</h5>
          <p class="card-text">Generate bills and final receipts for guests.</p>
          <a href="/billing/bill_generator.php" class="btn btn-dark w-100">Preview Bills</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("footer.php"); ?>
