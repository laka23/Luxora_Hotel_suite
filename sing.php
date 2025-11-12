<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "hotel_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission check
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars(trim($_POST["firstName"]));
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        die("This email is already registered.");
    }

    // Password check
    if ($password !== $confirmPassword) {
        die(" Passwords do not match.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $_SESSION["username"] = $firstName;

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rusithnipunalakshan@gmail.com'; 
            $mail->Password = 'qdbc lttt newa bqte';   
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('rusithnipunalakshan@gmail.com', 'Hotel System');
            $mail->addAddress($email, $firstName);
            $mail->isHTML(true);
            $mail->Subject = 'Welcome to Our Hotel!';
            $mail->Body = "Hi $firstName, <br>Thank you for registering!";

            $mail->send();
            echo "Registration successful! Email sent.";
            
            header("Location: MyBookings.php");
        } catch (Exception $e) {
            echo "egistered, but email failed: {$mail->ErrorInfo}";
        }

    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
