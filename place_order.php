<?php
require 'config/db_connect.php';
require 'includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode($_POST['cart_data'], true);
    if (!$cart || empty($cart)) {
        header("Location: index.php?error=empty");
        exit;
    }
    $ref = generate_order_ref();
    $name = sanitize_input($_POST['customer_name']);
    $contact = sanitize_input($_POST['contact_number']);
    $fulfillment = $_POST['fulfillment'];
    $payment = $_POST['payment_method'];
    $gcash_ref = !empty($_POST['gcash_ref']) ? sanitize_input($_POST['gcash_ref']) : null;
    $address = !empty($_POST['delivery_address']) ? sanitize_input($_POST['delivery_address']) : null;
    $delivery_fee = $fulfillment === 'delivery' ? 80.00 : 0.00;
    $grand_total = $delivery_fee;
    foreach ($cart as $item) {
        $grand_total += $item['price'] * $item['qty'];
    }
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO orders 
            (order_reference, customer_name, contact_number, order_type, fulfillment, delivery_address, delivery_fee, payment_method, gcash_ref, total_amount)
            VALUES (?, ?, ?, 'standard', ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$ref, $name, $contact, $fulfillment, $address, $delivery_fee, $payment, $gcash_ref, $grand_total]);
        
        $order_id = $pdo->lastInsertId();
        
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
        foreach ($cart as $item) {
            $sub = $item['price'] * $item['qty'];
            $stmtItem->execute([$order_id, $item['id'], $item['qty'], $sub]);
        }
        
        $pdo->commit();
        header("Location: order_status.php?ref=$ref&success=1");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Order failed: " . htmlspecialchars($e->getMessage()));
    }
}
header("Location: index.php");
?>