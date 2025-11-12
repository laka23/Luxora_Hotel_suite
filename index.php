<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="style.css">

  <meta charset="UTF-8">
  <title>Admin Dashboard</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(120deg, #00c6ff, #0072ff);
      color: #333;
      padding: 30px;
    }

    .dashboard-container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .dashboard-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .dashboard-header h2 {
      font-weight: 700;
      color: #0072ff;
    }
  </style>
</head>
<body>

  <div class="dashboard-container">
    <div class="dashboard-header">
      <h2>Welcome, Admin!</h2>
      <p>This is your admin dashboard. Use the navigation above to manage your bookings and profile.</p>
    </div>

    <!-- Add admin dashboard content here -->

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
