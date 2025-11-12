<?php
// Session start if needed
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Prime System</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="/assets/css/style.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #f8f9fa;
    }
    .navbar {
      background-color: #1f1f2e;
    }
    .navbar-brand {
      color: #fff;
      font-weight: 600;
    }
    .navbar-nav .nav-link {
      color: #ddd !important;
    }
    .navbar-nav .nav-link:hover {
      color: #ffffff !important;
    }
  </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/dashboard/clerk_dashboard.php">
      <i class="fas fa-hotel me-2"></i>Hotel Prime
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/checkin/checkin.php"><i class="fas fa-sign-in-alt me-1"></i> Check-In</a></li>
        <li class="nav-item"><a class="nav-link" href="/checkout/checkout.php"><i class="fas fa-sign-out-alt me-1"></i> Check-Out</a></li>
        <li class="nav-item"><a class="nav-link" href="/billing/service_charges.php"><i class="fas fa-concierge-bell me-1"></i> Services</a></li>
        <li class="nav-item"><a class="nav-link" href="/billing/bill_generator.php"><i class="fas fa-file-invoice-dollar me-1"></i> Bill Preview</a></li>
        <li class="nav-item"><a class="nav-link" href="/auth/logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid mt-4">
