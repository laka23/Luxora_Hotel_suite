<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Resend OTP request
    if (isset($_POST["resend"])) {
        if (isset($_SESSION["otp_email"])) {
            $email = $_SESSION["otp_email"];
            $otp = rand(100000, 999999);
            $_SESSION["otp"] = $otp;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'rusithnipunalakshan@gmail.com';
                $mail->Password   = 'your_app_password_here';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('rusithnipunalakshan@gmail.com', 'Hotel Booking');
                $mail->addAddress($email);
                $mail->Subject = 'Resent OTP Code';
                $mail->Body    = "Hello,\n\nYour new OTP code is: $otp\nIt is valid for 5 minutes.\n\n- Hotel Booking System";

                $mail->send();
                $_SESSION["error"] = " OTP resent successfully!";
                header("Location: verifycode.html");
                exit;
            } catch (Exception $e) {
                $_SESSION["error"] = " OTP resend failed. Error: {$mail->ErrorInfo}";
                header("Location: verifycode.html");
                exit;
            }
        } else {
            $_SESSION["error"] = " Session expired. Try again.";
            header("Location: verifycode.html");
            exit;
        }
    }

    // OTP verify
    $userOtp = $_POST["otp"];
    if (isset($_SESSION["otp"]) && isset($_SESSION["otp_email"])) {
        $storedOtp = $_SESSION["otp"];
        if ($userOtp == $storedOtp) {
            unset($_SESSION["otp"]);
            header("Location: New_Password.html");
            exit;
        } else {
            $_SESSION["error"] = "Invalid OTP";
            header("Location: verifycode.html");
            exit;
        }
    } else {
        $_SESSION["error"] = "Session expired. Try again.";
        header("Location: verifycode.html");
        exit;
    }
} else {
    header("Location: verifycode.html");
    exit;
}
