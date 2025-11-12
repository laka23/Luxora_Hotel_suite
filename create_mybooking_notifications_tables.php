<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create mybooking table without foreign key constraints
$mybooking_sql = "CREATE TABLE IF NOT EXISTS mybooking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    num_adults INT NOT NULL,
    num_children INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if ($conn->query($mybooking_sql) === TRUE) {
    echo 'Table \'mybooking\' created successfully or already exists.<br>';
} else {
    echo 'Error creating \'mybooking\' table: ' . $conn->error . '<br>';
}

// Create notifications table
$notifications_sql = "CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    icon VARCHAR(50) DEFAULT 'fa-bell',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

if ($conn->query($notifications_sql) === TRUE) {
    echo 'Table \'notifications\' created successfully or already exists.<br>';
} else {
    echo 'Error creating \'notifications\' table: ' . $conn->error . '<br>';
}

$conn->close();
?>
