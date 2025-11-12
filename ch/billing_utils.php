<?php
// billing_utils.php – Reusable functions for billing logic

// Calculate tax (e.g., 10% VAT)
function calculate_tax($subtotal, $rate = 0.10) {
    return round($subtotal * $rate, 2);
}

// Format price as LKR currency
function format_currency($amount) {
    return "LKR " . number_format($amount, 2);
}

// Calculate split payment across multiple methods
function split_payment($total, $method1 = "cash", $percent1 = 50) {
    $amount1 = round(($total * $percent1) / 100, 2);
    $amount2 = $total - $amount1;
    return [
        $method1 => $amount1,
        'other' => $amount2
    ];
}

// Sum services for a reservation
function get_service_total($conn, $reservation_id) {
    $stmt = $conn->prepare("SELECT SUM(cost) AS total FROM services WHERE reservation_id = ?");
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}
