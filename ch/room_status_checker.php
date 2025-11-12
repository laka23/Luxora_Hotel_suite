<?php
// middleware/room_status_checker.php

function is_room_available($conn, $room_id) {
    $stmt = $conn->prepare("SELECT status FROM rooms WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return ($result && $result['status'] === 'available');
}
