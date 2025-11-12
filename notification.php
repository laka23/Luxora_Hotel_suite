<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: loging.html");
    exit;
}

// Database connection (same as MyBookings.php)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_db";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID
$email = $_SESSION['email'];
$user_query = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Fetch all notifications
$notification_query = "SELECT * FROM notifications 
                      WHERE user_id = ? 
                      ORDER BY created_at DESC";
$stmt = $conn->prepare($notification_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notification_result = $stmt->get_result();

$notifications = [];
while ($row = $notification_result->fetch_assoc()) {
    $notifications[] = $row;
}

// Mark all as read when page loads
$mark_read_query = "UPDATE notifications SET is_read = TRUE WHERE user_id = ?";
$stmt = $conn->prepare($mark_read_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Notifications | Harborlights</title>
    <!-- Include your CSS styles here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Include your notification panel styles here */
        /* ... */
    </style>
</head>
<body>
    <!-- Include your header here -->
    
    <main class="main-container">
        <div class="page-header">
            <h1 class="page-title">All Notifications</h1>
        </div>
        
        <div class="bookings-card">
            <div class="bookings-header">
                <h2 class="section-title">Your Notifications</h2>
            </div>
            
            <div class="bookings-content">
                <?php if (empty($notifications)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-bell-slash"></i>
                        </div>
                        <h3 class="empty-title">No Notifications Found</h3>
                        <p class="empty-text">You don't have any notifications yet.</p>
                    </div>
                <?php else: ?>
                    <ul class="notification-list-full">
                        <?php foreach ($notifications as $notification): 
                            $icon = $notification['icon'] ?? 'fa-bell';
                        ?>
                        <li class="notification-item-full">
                            <div class="notification-icon">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></div>
                                <div class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></div>
                                <div class="notification-time">
                                    <?php 
                                        $created = new DateTime($notification['created_at']);
                                        echo $created->format('M j, Y g:i a');
                                    ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <!-- Include your footer here -->
</body>
</html>