<?php
// auth.php – Require login and restrict by role
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Optional: check for role access
function require_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        echo "<div class='container mt-5 alert alert-danger'>Access denied: You are not authorized to view this page.</div>";
        include("footer.php");
        exit();
    }
}
