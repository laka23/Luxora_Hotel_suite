<?php
// Start session and include database connection
session_start();
$conn = new mysqli("localhost", "root", "", "hotel_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Initialize variables
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$Phone = $_POST['Phone'] ?? '';
$address = $_POST['address'] ?? '';
$profile_picture = '';

// Handle file upload
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Generate unique filename
    $file_ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $filename = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
    $target_path = 'uploads/' . $filename;

    // Move uploaded file
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
        $profile_picture = $target_path;
    }
}

// Prepare SQL query (using prepared statements for security)
$sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, Phone = ?, address = ?". ($profile_picture ? ", profile_picture = ?" : "") . " WHERE id = ?";

$stmt = $conn->prepare($sql);

if ($profile_picture) {
    $stmt->bind_param("ssssssi", 
        $first_name, 
        $last_name, 
        $email, 
        $Phone, 
        $address, 
        $profile_picture, 
        $user_id);
} else {
    $stmt->bind_param("sssssi", 
        $first_name, 
        $last_name, 
        $email, 
        $Phone, 
        $address, 
        $user_id);
}

// Execute the query
if ($stmt->execute()) {
    $_SESSION['success_message'] = "Profile updated successfully!";
} else {
    $_SESSION['error_message'] = "Error updating profile: " . $conn->error;
}

$stmt->close();
$conn->close();

// Redirect back to profile page
header("Location: profile.php");
exit();
?>