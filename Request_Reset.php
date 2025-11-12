<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();

$email = $_POST["email"];

// Generate OTP
$otp = rand(100000, 999999);

// Save to session
$_SESSION["otp"] = $otp;
$_SESSION["otp_email"] = $email;

// Send email
$mail = new PHPMailer(true);

try {
    // SMTP Settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rusithnipunalakshan@gmail.com'; 
    $mail->Password   = 'qdbc lttt newa bqte'; // Make sure this is your Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Email content
    $mail->setFrom('bluesky@hotel.com', 'Hotel Booking');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "Hello,\n\nYour OTP code is: $otp\nIt is valid for 5 minutes.\n\n- Hotel Booking System";

    $mail->send(); // send email

    // Redirect after sending email
    header("Location: verifycode.html");
    exit;

} catch (Exception $e) {
    echo "❌ OTP sending failed. Mailer Error: {$mail->ErrorInfo}";
}
?>
