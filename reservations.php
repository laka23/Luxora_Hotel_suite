<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db.php';

// Fetch reservation records...


$sql = "SELECT * FROM reservations ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Reservations</title>
    <link rel="stylesheet" href="style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #ffecd2, #fcb69f);
            padding: 40px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        h2 {
            color: #ff6f61;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            font-size: 0.95rem;
        }
        th {
            background-color: #ff6f61;
            color: white;
        }
        tr:hover {
            background-color: #fff3e0;
        }
        .status-booked { color: green; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
        .status-no_show { color: orange; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fa-solid fa-clipboard-list"></i> Reservation Records</h2>

    <!-- Image Carousel -->
    <div id="roomImagesCarousel" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner" style="max-height: 400px; overflow: hidden;">
            <div class="carousel-item active">
                <img src="https://www.redrockresort.com/wp-content/uploads/2020/12/RR-Standard-2-Queen.jpg" class="d-block w-100" alt="Room Image 1" style="object-fit: cover; height: 400px;">
            </div>
            <div class="carousel-item">
                <img src="https://serenediva.lk/aws_server_uk/uploads/2019/11/27//2019112716593126432.jpg" class="d-block w-100" alt="Room Image 2" style="object-fit: cover; height: 400px;">
            </div>
            <div class="carousel-item">
                <img src="https://s3-us-west-1.amazonaws.com/sfo777/IMG_8683-M_0.jpg" class="d-block w-100" alt="Room Image 3" style="object-fit: cover; height: 400px;">
            </div>
            <div class="carousel-item">
                <img src="https://sthotelsmalta.com/wp-content/uploads/2022/06/modern-luxury-bedroom-suite-and-bathroom-with-working-table-scaled.jpg" class="d-block w-100" alt="Room Image 4" style="object-fit: cover; height: 400px;">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#roomImagesCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#roomImagesCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-start">Name</th>
                <th class="text-start">Email</th>
                <th class="text-center">Phone</th>
                <th class="text-start">Room Type</th>
                <th class="text-center">Check-In</th>
                <th class="text-center">Check-Out</th>
                <th class="text-center">Payment</th>
                <th class="text-center">Status</th>
                <th class="text-center">Booked At</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $statusClass = strtolower(str_replace(' ', '_', $row['status'])); ?>
                    <tr>
                        <td class="text-center"><?= $row['id'] ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['guest_name']) ?></td>
                        <td class="text-start"><?= htmlspecialchars($row['email']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['phone']) ?></td>
                        <td class="text-start"><?= $row['room_type'] ?></td>
                        <td class="text-center"><?= $row['check_in'] ?></td>
                        <td class="text-center"><?= $row['check_out'] ?></td>
                        <td class="text-center"><?= $row['payment_method'] ?></td>
                        <td class="status-<?= $statusClass ?> text-center"><?= ucfirst($row['status']) ?></td>
                        <td class="text-center"><?= $row['created_at'] ?></td>
                        <td class="text-center">
                            <a href="update_reservation.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                            <form method="POST" action="cancel_reservation.php" onsubmit="return confirm('Are you sure you want to cancel this reservation?');" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" class="text-center">No reservations found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="container mt-3 text-center">
<a href="export_reservations.php" class="btn btn-success me-2">Export to Excel</a>
    <a href="export_report_pdf.php" class="btn btn-danger">Export PDF Report</a>
</div>

<script src="script.js"></script>

</body>
</html>
