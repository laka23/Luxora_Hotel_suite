<?php
session_start();
include("hotel-reservation-system2/includes/db.php");
include("hotel-reservation-system2/includes/auth.php");
require_role('admin'); // Only admin can add users

// Debug: output session data for diagnosis
echo "<pre>Session Data:\n";
print_r($_SESSION);
echo "</pre>";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $username, $hashed_password, $role);
            if ($insert_stmt->execute()) {
                $success = "User created successfully.";
            } else {
                $error = "Failed to create user.";
            }
        }
        $stmt->close();
    }
}
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">
    <h2>Add New User</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <form method="POST" action="user_profile.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required />
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="clerk">Clerk</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
