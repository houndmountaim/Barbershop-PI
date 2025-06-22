<?php

function formatPrice($price) {
    return "Rp " . number_format($price, 0, ',', '.');
}

function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}
?>