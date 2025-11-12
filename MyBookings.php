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
        window.location.href = 'loging.html';
    </script>";
    exit;
}


$email = $_SESSION['email'];

// Get user ID
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
        $sql = "SELECT * FROM reservations WHERE email = ? AND (status = 'booked' OR status = 'confirmed') AND check_in >= '$today' ORDER BY created_at DESC";
        break;
    case 'completed':
        $sql = "SELECT * FROM reservations WHERE email = ? AND status = 'completed' ORDER BY created_at DESC";
        break;
    case 'cancelled':
        $sql = "SELECT * FROM reservations WHERE email = ? AND status = 'cancelled' ORDER BY created_at DESC";
        break;
    case 'all':
    default:
        $sql = "SELECT * FROM reservations WHERE email = ? ORDER BY created_at DESC";
        break;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$reservations_result = $stmt->get_result();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | Luxora Hotel suite</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=notification_add" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="Css/mybook.css">
    <style>
        /* Additional styles for the reservation list */
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
        
        @media (max-width: 768px) {
            .reservations-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <header class="header" id="header">
        <div class="container header-container">
            <a href="index.html" class="logo">Luxora<span>Hotel suite</span></a>
            <button class="nav-toggle" id="navToggle">
                <i class="fas fa-bars"></i>
            </button>
            <nav class="nav" id="nav">
                <ul class="nav-menu" id="navMenu">
                    <li class="nav-item"><a href="index.html#home" class="nav-link active">Home</a></li>
                    <li class="nav-item"><a href="index.html#rooms" class="nav-link">Rooms</a></li>
                    <li class="nav-item"><a href="index.html#services" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="index.html#about" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="index.html#contact" class="nav-link">Contact</a></li>
                </ul>
            </nav>
            
            <div class="user-menu">
                <button class="btn btn-primary"><a href="profile.php">Profile</a></button>
            </div>
           
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
                            <div class="notification-icon">
                                <i class="fas fa-bell-slash"></i>
                            </div>
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
                            <div class="notification-icon">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
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
                    <a href="notification.php" class="view-all-btn">
                        View All Notifications <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main-container">
        <div class="page-header">
            <div>
                <br>
                <br>
            </div>
            <br>
            <br>
            <a href="booking.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Booking
            </a>
        </div>
        
        <div class="bookings-card">
            <div class="bookings-header">
                <h2 class="section-title">Your Reservations</h2>
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
                            <th>#</th>
                            <th>Name</th>
                            <th>Room Type</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Booked At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($reservations_result->num_rows > 0): ?>
                            <?php while ($row = $reservations_result->fetch_assoc()): ?>
                                <?php $statusClass = strtolower(str_replace(' ', '_', $row['status'])); ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td class="text-start"><?= htmlspecialchars($row['guest_name']) ?></td>
                                    <td class="text-start"><?= htmlspecialchars($row['room_type']) ?></td>
                                    <td><?= date('M j, Y', strtotime($row['check_in'])) ?></td>
                                    <td><?= date('M j, Y', strtotime($row['check_out'])) ?></td>
                                    <td><?= htmlspecialchars($row['payment_method']) ?></td>
                                    <td class="status-<?= $statusClass ?>"><?= ucfirst($row['status']) ?></td>
                                    <td><?= date('M j, Y H:i', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'booked' || $row['status'] == 'confirmed'): ?>
                                            <a href="update_reservation.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
                                            <form method="POST" action="cancel_reservation.php" onsubmit="return confirm('Are you sure you want to cancel this reservation?');" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                            </form>
                                        <?php elseif ($row['status'] == 'completed'): ?>
                                            <button class="btn btn-info btn-sm">Review</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No reservations found for your account.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>Harborlights</h3>
                    <p>Where elegance meets comfort. Your luxurious stay awaits by the harbor.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#rooms">Rooms</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Contact Info</h3>
                    <ul class="footer-contact">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Harbor Street, Seaview City</li>
                        <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope"></i> info@harborlights.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2025 Harborlights Hotel & Resort. All Rights Reserved.
            </div>
        </div>
    </footer>
    
    <script>
        // Notification Panel Toggle
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationOverlay = document.getElementById('notificationOverlay');
        const notificationPanel = document.getElementById('notificationPanel');
        const notificationClose = document.getElementById('notificationClose');
        
        // Open notification panel
        notificationBtn.addEventListener('click', function() {
            notificationOverlay.classList.add('active');
            notificationPanel.classList.add('active');
            
            // Animate notification items one by one
            const notificationItems = document.querySelectorAll('.notification-item');
            notificationItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('show');
                }, index * 100);
            });
            
            // Mark notifications as read (remove badge)
            if (this.querySelector('.notification-badge')) {
                this.querySelector('.notification-badge').style.display = 'none';
            }
        });
        
        // Close notification panel
        function closeNotificationPanel() {
            notificationOverlay.classList.remove('active');
            notificationPanel.classList.remove('active');
            
            // Reset animation for next time
            const notificationItems = document.querySelectorAll('.notification-item');
            notificationItems.forEach(item => {
                item.classList.remove('show');
            });
        }
        
        notificationOverlay.addEventListener('click', closeNotificationPanel);
        notificationClose.addEventListener('click', closeNotificationPanel);
        
        // Close when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && notificationPanel.classList.contains('active')) {
                closeNotificationPanel();
            }
        });

        // Mark notifications as read via AJAX
        notificationBtn.addEventListener('click', function() {
            fetch('mark_notifications_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'user_id=<?php echo $user_id; ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && this.querySelector('.notification-badge')) {
                    this.querySelector('.notification-badge').style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>