<?php
// middleware/guest_type_handler.php

function flag_guest_type($is_vip, $is_walkin, $checkin_date) {
    $notes = [];

    if ($is_vip) {
        $notes[] = "VIP guest flagged.";
    }
    if ($is_walkin) {
        $notes[] = "Walk-in guest — ID scan may be required.";
    }
    $today = (new DateTime())->format('Y-m-d');
    if ($checkin_date < $today) {
        $notes[] = "Backdated check-in — manager override may be required.";
    }
    return implode(" ", $notes);
}
