<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_db";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {
        echo " Passwords do not match.";
        exit;
    }

    if (!isset($_SESSION["otp_email"])) {
        echo "Session expired or email not found.";
        exit;
    }

    $email = $_SESSION["otp_email"];
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password in DB
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);

    if ($stmt->execute()) {
        // Send confirmation email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rusithnipunalakshan@gmail.com';
            $mail->Password   = 'qdbc lttt newa bqte'; // App password 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('rusithnipunalakshan@gmail.com', 'Hotel Booking');
            $mail->addAddress($email);
            $mail->Subject = 'Password Changed';
            $mail->Body    = "Hello,\n\nYour password was changed successfully.\nIf this was not you, please contact us immediately.\n\n- Hotel Booking Team";

            $mail->send();
            //Notification messege 
            echo "<script> 
    alert(' Password changed and email sent!');
    window.location.href = 'MyBookings.php'; 
</script>";
        } catch (Exception $e) {
            echo "Password updated, but email failed: {$mail->ErrorInfo}";
        }
    } else {
        echo "Failed to update password.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
