<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

// Handle status updates or deletions if submitted
if (isset($_GET['action']) && isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];
    if ($_GET['action'] === 'confirm') {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'Preparing' WHERE order_id = ?");
        $stmt->execute([$order_id]);
    } elseif ($_GET['action'] === 'complete') {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'Completed' WHERE order_id = ?");
        $stmt->execute([$order_id]);
    } elseif ($_GET['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->execute([$order_id]);
    }
    header("Location: orders.php");
    exit;
}

// Fetch all orders
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders — Little Chef Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0b0b0b; color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { background-color: #161616; border: 1px solid #332222; color: #fff; border-radius: 0.75rem; }
        .navbar { background-color: #121212 !important; border-bottom: 1px solid #332222; }
        .table { color: #f8f9fa; background-color: #161616; }
        .table-dark { background-color: #121212; border-color: #332222; }
        .btn-warning { background-color: #e6b800; border: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark px-4 mb-4">
    <a href="dashboard.php" class="navbar-brand fw-bold text-warning">🍣 Little Chef — Admin</a>
    <div>
        <a href="dashboard.php" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
        <a href="products.php" class="btn btn-outline-light btn-sm me-2">Products</a>
        <a href="inventory.php" class="btn btn-outline-light btn-sm me-2">Inventory</a>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container my-4">
    <h2 class="text-warning mb-4 fw-bold">📋 Orders Management</h2>

    <div class="card p-4 shadow">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order Ref</th>
                        <th>Customer Name</th>
                        <th>Type / Payment</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No active orders found.</td></tr>
                    <?php else: foreach ($orders as $o): ?>
                    <tr>
                        <td class="fw-bold text-warning"><?= htmlspecialchars($o['order_reference'] ?? 'LC-00' . $o['order_id']) ?></td>
                        <td><?= htmlspecialchars($o['customer_name'] ?? 'Guest Customer') ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($o['payment_method'] ?? 'Cash') ?></span></td>
                        <td>₱<?= number_format($o['total_amount'] ?? 0, 2) ?></td>
                        <td>
                            <?php 
                                $status = $o['status'] ?? 'Pending';
                                $badgeClass = 'bg-warning text-dark';
                                if ($status === 'Preparing') $badgeClass = 'bg-info text-dark';
                                if ($status === 'Completed') $badgeClass = 'bg-success';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                        </td>
                        <td>
                            <a href="order_view.php?id=<?= $o['order_id'] ?>" class="btn btn-sm btn-outline-light">View</a>
                            <?php if ($status === 'Pending'): ?>
                            <a href="orders.php?action=confirm&id=<?= $o['order_id'] ?>" class="btn btn-sm btn-warning">Confirm</a>
                            <?php endif; ?>
                            <a href="orders.php?action=delete&id=<?= $o['order_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this order?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>