<?php require 'config/db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Order Request — Little Chef</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:700px">
    <h2 class="text-center mb-4">📋 Bulk Order Request</h2>
    <p class="text-muted text-center mb-4">Minimum quantity: 20 pcs / item • Please submit at least 3 days in advance</p>
    
    <form method="POST" action="submit_bulk.php" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="tel" name="contact_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Desired Date & Time</label>
            <input type="datetime-local" name="desired_datetime" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Select Products & Quantities</label>
            <?php
            $prodList = $pdo->query("SELECT * FROM products WHERE availability_status='available'")->fetchAll();
            foreach ($prodList as $p):
            ?>
            <div class="d-flex align-items-center border p-2 mb-2 rounded bg-white">
                <div class="flex-grow-1">
                    <strong><?= htmlspecialchars($p['name']) ?></strong> — ₱<?= number_format($p['price'],2) ?>/order
                </div>
                <input type="number" name="qty[<?= $p['product_id'] ?>]" min="0" value="0" class="form-control ms-2" style="width:80px">
            </div>
            <?php endforeach; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Fulfillment</label>
            <select name="fulfillment" class="form-select" required>
                <option value="pickup">Pickup</option>
                <option value="delivery">Lalamove Delivery</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" required>
                <option value="gcash">GCash</option>
                <option value="cash">Cash</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Special Instructions / Notes</label>
            <textarea name="special_instructions" class="form-control" rows="3" placeholder="Special requests, dietary needs, etc."></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit Bulk Request</button>
        <a href="index.php" class="btn btn-outline-secondary w-100 mt-2">← Back to Menu</a>
    </form>
</div>
</body>
</html>