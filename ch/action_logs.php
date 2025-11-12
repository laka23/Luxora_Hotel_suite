<?php
// logs/action_logs.php

function log_action($conn, $user_id, $type, $message) {
    $stmt = $conn->prepare("INSERT INTO action_logs (user_id, action_type, message, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $user_id, $type, $message);
    $stmt->execute();
}
