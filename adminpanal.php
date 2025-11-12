<?php
session_start();


$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");


if (!isset($_SESSION['email'])) {
    echo "<script>
        alert('Please log in first.');
        window.location.href = 'loging-admin.html';
    </script>";
    exit;
}


$email = $_SESSION['email'];

// Get admin ID
$user_query = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('User not found.');</script>";
    exit;
}

$user = $result->fetch_assoc();
$user_id = $user['id'];


$notification_query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10";
$stmt = $conn->prepare($notification_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notification_result = $stmt->get_result();

$notifications = [];
$unread_count = 0;
while ($row = $notification_result->fetch_assoc()) {
    $notifications[] = $row;
    if (!$row['is_read']) {
        $unread_count++;
    }
}


$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

switch ($filter) {
    case 'upcoming':
        $today = date('Y-m-d');
        $sql = "SELECT * FROM reservations WHERE (status = 'booked' OR status = 'confirmed') AND check_in >= '$today' ORDER BY created_at DESC";
        break;
    case 'completed':
        $sql = "SELECT * FROM reservations WHERE status = 'completed' ORDER BY created_at DESC";
        break;
    case 'cancelled':
        $sql = "SELECT * FROM reservations WHERE status = 'cancelled' ORDER BY created_at DESC";
        break;
    case 'all':
    default:
        $sql = "SELECT * FROM reservations ORDER BY created_at DESC";
        break;
}

$reservations_result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Luxora Hotel suite</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Css/mybook.css">
    <style>
        /* Admin-specific styles */
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .admin-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .admin-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .export-section {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }
        
        /* Reservation table styles */
        .reservations-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .reservations-table th, .reservations-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .reservations-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .reservations-table tr:hover {
            background-color: #f5f5f5;
        }
        
        /* Status badges */
        .status-booked, .status-confirmed {
            background-color: #4CAF50;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        .status-cancelled {
            background-color: #f44336;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        .status-checked_in {
            background-color: #2196F3;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        .status-checked_out, .status-completed {
            background-color: #9C27B0;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        .status-no_show {
            background-color: #607D8B;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        
        /* Filter tabs */
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .filter-tab {
            padding: 8px 16px;
            border-radius: 20px;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .filter-tab.active {
            background-color: #4a6bff;
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .reservations-table {
                display: block;
                overflow-x: auto;
            }
            
            .admin-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="header" id="header">
        <div class="container header-container">
            <a href="index.html" class="logo">Luxora <span>Hotel suite</span> <small>(Admin)</small></a>
            <div class="user-menu">
                <button class="btn btn-primary"><a href="profile.php">Admin Profile</a></button>
                <button class="btn btn-danger"><a href="logout.php">Logout</a></button>
            </div>
        </div>
    </header>
    <br>
    <br>
    <br>
    <br>
    <br>
    
    <main class="admin-container">
        <div class="admin-actions">
            <a href="booking.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Booking
            </a>
            <a href="add_room.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Rooms
            </a>
            <a href="admin_rooms.php" class="btn btn-primary">
                <i class="fas fa-cogs"></i> Room Management
            </a>
            <a href="admin_rooms.php" class="btn btn-primary">
                <i class="fas fa-cogs"></i> Checking 
            </a>
        </div>

        <div class="admin-card">
            <div class="bookings-header">
                <h2 class="section-title">All Reservations</h2>
                <div class="filter-tabs">
                    <?php
                    function filter_class($filter_name, $current_filter) {
                        return $filter_name === $current_filter ? 'filter-tab active' : 'filter-tab';
                    }
                    $current_filter = $_GET['filter'] ?? 'all';
                    ?>
                    <a href="?filter=all" class="<?php echo filter_class('all', $current_filter); ?>">All</a>
                    <a href="?filter=upcoming" class="<?php echo filter_class('upcoming', $current_filter); ?>">Upcoming</a>
                    <a href="?filter=completed" class="<?php echo filter_class('completed', $current_filter); ?>">Completed</a>
                    <a href="?filter=cancelled" class="<?php echo filter_class('cancelled', $current_filter); ?>">Cancelled</a>
                </div>
            </div>
            
            <div class="bookings-content">
                <table class="reservations-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Guest Name</th>
                            <th>Email</th>
                            <th>Room Type</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($reservations_result->num_rows > 0): ?>
                            <?php while ($row = $reservations_result->fetch_assoc()): ?>
                                <?php $statusClass = strtolower(str_replace(' ', '_', $row['status'])); ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['guest_name']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['room_type']) ?></td>
                                    <td><?= date('M j, Y', strtotime($row['check_in'])) ?></td>
                                    <td><?= date('M j, Y', strtotime($row['check_out'])) ?></td>
                                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                                    <td class="status-<?= $statusClass ?>"><?= ucfirst($row['status']) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="edit_reservation.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <?php if ($row['status'] == 'booked' || $row['status'] == 'confirmed'): ?>
                                                <form method="POST" action="cancel_reservation.php" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                                </form>
                                            <?php elseif ($row['status'] == 'checked_in'): ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No reservations found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="export-section">
            <h2 class="section-title">Export Reservations</h2>
            <div class="export-actions">
                <a href="export_reservations_excel.php" class="btn btn-primary">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </a>
                <a href="export_reservations_pdf.php" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Export to PDF
                </a>
                <a href="export_reservations_csv.php" class="btn btn-primary">
                    <i class="fas fa-file-csv"></i> Export to CSV
                </a>
            </div>
        </div>
    </main>

    <!-- Notification Panel -->
    <button class="notification-btn" id="notificationBtn">
        <i class="fas fa-bell"></i>
        <?php if ($unread_count > 0): ?>
            <span class="notification-badge"><?php echo $unread_count; ?></span>
        <?php endif; ?>
    </button>
    <div class="notification-overlay" id="notificationOverlay"></div>
    <div class="notification-panel" id="notificationPanel">
        <div class="notification-header">
            <h3>Notifications</h3>
            <button class="notification-close" id="notificationClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="notification-list">
            <?php if (empty($notifications)): ?>
                <li class="notification-item">
                    <div class="notification-icon"><i class="fas fa-bell-slash"></i></div>
                    <div class="notification-content">
                        <div class="notification-title">No Notifications</div>
                        <div class="notification-message">You don't have any notifications yet.</div>
                    </div>
                </li>
            <?php else: ?>
                <?php foreach ($notifications as $notification): 
                    $icon = $notification['icon'] ?? 'fa-bell';
                    $unread_class = $notification['is_read'] ? '' : 'unread';
                ?>
                <li class="notification-item <?php echo $unread_class; ?>">
                    <div class="notification-icon"><i class="fas <?php echo $icon; ?>"></i></div>
                    <div class="notification-content">
                        <div class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></div>
                        <div class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></div>
                        <div class="notification-time">
                            <?php 
                                $created = new DateTime($notification['created_at']);
                                echo $created->format('M j, g:i a');
                            ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <div class="notification-footer">
            <a href="notification.php" class="view-all-btn">View All Notifications <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>

    <script>
    // Notification Panel Toggle
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationOverlay = document.getElementById('notificationOverlay');
    const notificationPanel = document.getElementById('notificationPanel');
    const notificationClose = document.getElementById('notificationClose');
    
    notificationBtn.addEventListener('click', function () {
        notificationOverlay.classList.add('active');
        notificationPanel.classList.add('active');
    
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach((item, index) => {
            setTimeout(() => item.classList.add('show'), index * 100);
        });
    
        fetch('mark_notifications_read.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'user_id=<?php echo $user_id; ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const badge = notificationBtn.querySelector('.notification-badge');
                if (badge) badge.style.display = 'none';
            }
        });
    });
    
    function closeNotificationPanel() {
        notificationOverlay.classList.remove('active');
        notificationPanel.classList.remove('active');
        document.querySelectorAll('.notification-item').forEach(item => item.classList.remove('show'));
    }
    
    notificationOverlay.addEventListener('click', closeNotificationPanel);
    notificationClose.addEventListener('click', closeNotificationPanel);
    
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && notificationPanel.classList.contains('active')) {
            closeNotificationPanel();
        }
    });
    </script>
</body>
</html>