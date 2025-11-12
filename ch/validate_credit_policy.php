<?php
// middleware/validate_credit_policy.php

function enforce_credit_card_policy($is_walkin, $card_number) {
    $now = new DateTime();
    $hour = (int)$now->format('H');

    if (!$is_walkin && $hour < 19 && empty($card_number)) {
        return "Credit card is required for reservations before 7 PM.";
    }
    return null;
}
