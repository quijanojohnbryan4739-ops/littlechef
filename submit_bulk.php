<?php
require 'config/db_connect.php';
require 'includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref = generate_order_ref();
    $name = sanitize_input($_POST['customer_name']);
    $contact = sanitize_input($_POST['contact_number']);
    $fulfillment = $_POST['fulfillment'];
    $payment = $_POST['payment_method'];
    $notes = sanitize_input($_POST['special_instructions'] ?? '');
    $desired = $_POST['desired_datetime'];
    // Calculate totals
    $grand_total = $fulfillment==='delivery' ? 80 : 0;
    $items = [];
    foreach ($_POST['qty'] as $pid => $qty) {
        $qty = (int)$qty;
        if ($qty < 1) continue;
        $stmt = $pdo->prepare("SELECT price FROM products WHERE product_id=?");
        $stmt->execute([$pid]);
        $prod = $stmt->fetch();
        if ($prod) {
            $sub = $prod['price'] * $qty;
            $grand_total += $sub;
            $items[] = ['pid'=>$pid, 'qty'=>$qty, 'sub'=>$sub];
        }
    }
    if (empty($items)) {
        die("Please select at least one product.");
    }
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO orders 
            (order_reference, customer_name, contact_number, order_type, fulfillment, payment_method, special_instructions, desired_datetime, total_amount)
            VALUES (?, ?, ?, 'bulk', ?, ?, ?, ?, ?)");
        $stmt->execute([$ref, $name, $contact, $fulfillment, $payment, $notes, $desired, $grand_total]);
        $oid = $pdo->lastInsertId();
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
        foreach ($items as $i) {
            $stmtItem->execute([$oid, $i['pid'], $i['qty'], $i['sub']]);
        }
        $pdo->commit();
        header("Location: order_status.php?ref=$ref&bulk=1");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>