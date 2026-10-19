<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

// Fetch metrics
$product_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$order_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$low_stock_items = check_low_stock($pdo);

// Fetch recent orders for the dashboard widget
$recent_orders_stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
$recent_orders = $recent_orders_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Little Chef</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f2eb; /* Warm Beige Background */
            color: #2c221e;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .navbar {
            background-color: #1a1714 !important;
            border-bottom: 2px solid #d4c5b9;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #e3dcd2;
            color: #2c221e;
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(44, 34, 30, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 6px 20px rgba(44, 34, 30, 0.1);
        }
        .table {
            color: #2c221e;
        }
        .btn-warning {
            background-color: #d4a373;
            border: none;
            color: #fff;
            font-weight: 600;
        }
        .btn-warning:hover {
            background-color: #bc6c25;
            color: #fff;
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3 sticky-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-warning fs-4" href="dashboard.php">🍣 Little Chef — Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="adminNav">
            <div class="navbar-nav align-items-center gap-2">
                <a href="dashboard.php" class="btn btn-warning btn-sm active px-3">Dashboard</a>
                <a href="orders.php" class="btn btn-outline-light btn-sm px-3">Orders</a>
                <a href="products.php" class="btn btn-outline-light btn-sm px-3">Products</a>
                <a href="inventory.php" class="btn btn-outline-light btn-sm px-3">Inventory</a>
                <a href="../index.php" class="btn btn-outline-warning btn-sm px-3" target="_blank">View Shop</a>
                <a href="logout.php" class="btn btn-outline-danger btn-sm px-3 ms-lg-3">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container py-5">
    <!-- Welcome Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #2c221e;">Dashboard Overview</h2>
            <p class="text-muted mb-0">Welcome back, <?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?>! Here is what's happening at Little Chef today.</p>
        </div>
        <div>
            <span class="badge bg-white border text-dark p-2 shadow-sm">📅 <?= date('F j, Y') ?></span>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h5 class="text-muted text-uppercase fs-6 tracking-wide mb-2">Total Products</h5>
                <div class="stats-number text-dark"><?= $product_count ?></div>
                <a href="products.php" class="btn btn-sm btn-outline-dark mt-3">Manage Products →</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h5 class="text-muted text-uppercase fs-6 tracking-wide mb-2">Total Orders</h5>
                <div class="stats-number text-success"><?= $order_count ?></div>
                <a href="orders.php" class="btn btn-sm btn-outline-dark mt-3">Manage Orders →</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h5 class="text-muted text-uppercase fs-6 tracking-wide mb-2">Low Stock Alerts</h5>
                <div class="stats-number text-danger"><?= count($low_stock_items) ?></div>
                <a href="inventory.php" class="btn btn-sm btn-outline-dark mt-3">Check Inventory →</a>
            </div>
        </div>
    </div>

    <!-- Recent Orders Widget -->
    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0" style="color: #2c221e;">Recent Orders</h4>
            <a href="orders.php" class="btn btn-sm btn-warning">View All Orders</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order Reference</th>
                        <th>Customer</th>
                        <th>Payment / Type</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_orders)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No orders placed yet.</td>
                    </tr>
                    <?php else: foreach ($recent_orders as $ro): 
                        $status = $ro['status'] ?? 'Pending';
                        $badgeClass = 'bg-warning text-dark';
                        if ($status === 'Preparing') $badgeClass = 'bg-info text-dark';
                        if ($status === 'Completed') $badgeClass = 'bg-success';
                    ?>
                    <tr>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($ro['order_reference'] ?? 'LC-00' . $ro['order_id']) ?></td>
                        <td><?= htmlspecialchars($ro['customer_name'] ?? 'Guest Customer') ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($ro['payment_method'] ?? 'Cash') ?></span></td>
                        <td>₱<?= number_format($ro['total_amount'] ?? 0, 2) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= $status ?></span></td>
                        <td>
                            <a href="orders.php" class="btn btn-sm btn-outline-dark">Review</a>
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