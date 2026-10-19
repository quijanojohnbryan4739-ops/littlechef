<?php
require 'config/db_connect.php';
$ref = $_GET['ref'] ?? '';
$order = null;
if ($ref) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_reference=?");
    $stmt->execute([$ref]);
    $order = $stmt->fetch();
    if ($order) {
        $stmtItems = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id=p.product_id WHERE oi.order_id=?");
        $stmtItems->execute([$order['order_id']]);
        $items = $stmtItems->fetchAll();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status — Little Chef</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:600px">
    <h2 class="text-center mb-4">🔍 Track Your Order</h2>
    
    <?php if (!$order): ?>
    <form method="GET" class="card p-4 shadow mb-4">
        <label class="form-label">Enter Order Reference</label>
        <input type="text" name="ref" class="form-control form-control-lg text-center fw-bold" placeholder="LCH-XXXXXXXXXX" required>
        <button type="submit" class="btn btn-primary w-100 mt-3">Check Status</button>
    </form>
    <?php else: ?>
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Reference: <?= htmlspecialchars($order['order_reference']) ?></h5>
        </div>
        <div class="card-body">
            <div class="text-center py-3">
                <?php 
                $badge = match($order['status']) {
                    'pending' => 'bg-warning text-dark',
                    'confirmed' => 'bg-primary',
                    'preparing' => 'bg-info',
                    'out_for_delivery' => 'bg-purple',
                    'completed' => 'bg-success',
                    'rejected' => 'bg-danger'
                };
                ?>
                <span class="badge <?= $badge ?> fs-6 px-4 py-2">
                    <?= ucwords(str_replace('_',' ',$order['status'])) ?>
                </span>
            </div>
            
            <div class="progress mb-4" style="height:6px">
                <?php
                $steps = ['pending'=>20,'confirmed'=>40,'preparing'=>60,'out_for_delivery'=>80,'completed'=>100];
                $width = $steps[$order['status']] ?? 0;
                ?>
                <div class="progress-bar bg-success" style="width:<?= $width ?>%"></div>
            </div>
            <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>Type:</strong> <?= ucfirst($order['order_type']) ?> Order</p>
            <p><strong>Fulfillment:</strong> <?= ucfirst($order['fulfillment']) ?></p>
            <p><strong>Payment:</strong> <?= strtoupper($order['payment_method']) ?>
                <?= $order['gcash_ref'] ? " — Ref: {$order['gcash_ref']}" : '' ?>
            </p>
            <p><strong>Total:</strong> ₱<?= number_format($order['total_amount'], 2) ?></p>
            <p><strong>Submitted:</strong> <?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></p>
            
            <?php if (!empty($items)): ?>
            <hr>
            <h6>Order Items</h6>
            <?php foreach ($items as $item): ?>
            <div class="d-flex justify-content-between">
                <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                <span>₱<?= number_format($item['subtotal'],2) ?></span>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
            <a href="index.php" class="btn btn-secondary w-100 mt-4">Back to Menu</a>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>