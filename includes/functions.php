<?php
function sanitize_input(string $data): string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function generate_order_ref(): string {
    return 'LCH-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}

function check_low_stock(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM inventory WHERE quantity_in_stock <= low_stock_threshold");
    return $stmt->fetchAll();
}
?>