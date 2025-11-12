<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hotel_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = htmlspecialchars(trim($_POST["email"]));
    $password = $_POST["password"];

    // Get user from DB by email
    $stmt = $conn->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $firstName, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Password correct, set session
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $firstName;
            $_SESSION["email"] = $email;
            $_SESSION["logged_in"] = true;

            header("Location: adminpanal.php");
            exit;
        } else {
            // Password incorrect
            echo "<script>alert('Incorrect password.'); window.location.href = 'loging-admin.html';</script>";
            exit;
        }

    } else {
        echo "<script>alert('No user found with that email.'); window.location.href = 'loging-admin.html';</script>";
        exit;
    }

    $stmt->close();
    $conn->close();

} else {
    echo "<script>alert('Invalid request method.'); window.location.href = '-admin.html';</script>";
    exit;
}
?>
