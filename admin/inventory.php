<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

// Fetch inventory
$stmt = $pdo->query("SELECT * FROM inventory ORDER BY ingredient_name ASC");
$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management — Little Chef Admin</title>
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
        <a href="orders.php" class="btn btn-outline-light btn-sm me-2">Orders</a>
        <a href="products.php" class="btn btn-outline-light btn-sm me-2">Products</a>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning fw-bold">📦 Inventory Management</h2>
        <a href="inventory_add.php" class="btn btn-warning fw-bold">+ Add Ingredient</a>
    </div>

    <div class="card p-4 shadow">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr>
                        <th>Ingredient</th>
                        <th>Stock Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inventory)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No inventory items found. Click "+ Add Ingredient".</td></tr>
                    <?php else: foreach ($inventory as $inv): 
                        $qty = $inv['quantity_in_stock'];
                        $threshold = $inv['low_stock_threshold'] ?? 5;
                        $statusText = 'GOOD';
                        $badgeClass = 'bg-success';
                        if ($qty <= 0) {
                            $statusText = 'OUT';
                            $badgeClass = 'bg-danger';
                        } elseif ($qty <= $threshold) {
                            $statusText = 'LOW';
                            $badgeClass = 'bg-warning text-dark';
                        }
                    ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($inv['ingredient_name']) ?></td>
                        <td><?= $qty ?> <?= htmlspecialchars($inv['unit'] ?? 'pcs') ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= $statusText ?></span></td>
                        <td>
                            <a href="inventory_edit.php?id=<?= $inv['ingredient_id'] ?>" class="btn btn-sm btn-outline-light">Edit</a>
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