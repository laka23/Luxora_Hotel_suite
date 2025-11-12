<?php
// Connect to database
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $guest_name = $conn->real_escape_string(trim($_POST['guest_name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $room_type = $conn->real_escape_string(trim($_POST['room_type']));
    $check_in = $conn->real_escape_string(trim($_POST['check_in']));
    $check_out = $conn->real_escape_string(trim($_POST['check_out']));
    $payment_method = $conn->real_escape_string(trim($_POST['payment_method']));

    if (!$email) {
        echo "<script>
                alert('Invalid email address.');
                window.location.href = 'index.php';
              </script>";
        exit;
    }

    // Check for overlapping reservations for the same room_type
    $check_query = "
        SELECT COUNT(*) AS count FROM reservations
        WHERE room_type = '$room_type'
          AND status IN ('booked', 'confirmed', 'checked-in')
          AND (
            (check_in <= '$check_out' AND check_out >= '$check_in')
          )
    ";
    $check_result = $conn->query($check_query);
    $row = $check_result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "<script>
                alert('The selected room type is not available for the chosen dates. Please select different dates or room type.');
                window.location.href = 'booking.php';
              </script>";
        exit;
    }

    // Insert into database
    $sql = "INSERT INTO reservations 
            (guest_name, email, phone, room_type, check_in, check_out, payment_method, status) 
            VALUES 
            ('$guest_name', '$email', '$phone', '$room_type', '$check_in', '$check_out', '$payment_method', 'booked')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Reservation successful!');
                window.location.href = 'MyBookings.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $conn->error . "');
                window.location.href = 'index.php';
              </script>";
    }
} else {
    // Redirect GET requests to reservations.php
    header("Location: reservations.php");
    exit();
}
?>
