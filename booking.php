<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email'])) {
    echo "<script>
        alert('Please log in first.');
        window.location.href = 'loging.html';
    </script>";
    exit;
}

$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="style.css">

  <meta charset="UTF-8">
  <title>Hotel Reservation Form</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #00b09b, #96c93d);
      color: #333;
      padding: 30px;
    }

    .reservation-form {
      max-width: 700px;
      margin: auto;
      background: #f9f9f9;
      border-radius: 15px;
      padding: 40px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .form-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-header h2 {
      font-weight: 700;
      color: #00b09b;
    }

    .form-group i {
      margin-right: 8px;
      color: #00b09b;
    }

    .btn-reserve {
      background: #00b09b;
      color: white;
      font-weight: 600;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 10px;
      transition: 0.3s;
    }

    .btn-reserve:hover {
      background: #018f7a;
    }

    .room-image {
      width: 100%;
      border-radius: 15px;
      margin-bottom: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
      .reservation-form {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="reservation-form">
    <div class="form-header">
      <h2><i class="fa-solid fa-hotel"></i> Book Your Room</h2>
      <img src="https://cdn.prod.website-files.com/64d618bb0ccc37b64e1d6053/6718b6820112355ec0cc585c_BHC_facade.jpg" class="room-image" alt="Hotel Room">
    </div>

    <form action="reserve.php" method="POST">
      <div class="mb-3">
        <label class="form-label"><i class="fa-solid fa-user"></i> Full Name</label>
        <input type="text" class="form-control" name="guest_name" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa-solid fa-phone"></i> Phone Number</label>
        <input type="text" class="form-control" name="phone" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa-solid fa-bed"></i> Room Type</label>
        <select class="form-select" name="room_type" required>
          <option value="">Choose a room</option>
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

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label"><i class="fa-solid fa-calendar-check"></i> Check-in Date</label>
          <input type="date" class="form-control" name="check_in" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label"><i class="fa-solid fa-calendar-xmark"></i> Check-out Date</label>
          <input type="date" class="form-control" name="check_out" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fa-solid fa-money-bill-wave"></i> Payment Method</label>
        <select class="form-select" name="payment_method" required>
          <option value="Cash">Cash</option>
          <option value="Card">Credit / Debit Card</option>
        </select>
      </div>

      <button type="submit" class="btn-reserve"><i class="fa-solid fa-circle-check"></i> Reserve Now</button>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
