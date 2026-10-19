<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

// Fetch products
$stmt = $pdo->query("SELECT * FROM products ORDER BY product_id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management — Little Chef Admin</title>
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
        <a href="inventory.php" class="btn btn-outline-light btn-sm me-2">Inventory</a>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning fw-bold">🍱 Product Management</h2>
        <a href="product_add.php" class="btn btn-warning fw-bold">+ Add Product</a>
    </div>

    <div class="card p-4 shadow">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No products found. Click "+ Add Product" to begin.</td></tr>
                    <?php else: foreach ($products as $p): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($p['name']) ?></td>
                        <td class="text-warning">₱<?= number_format($p['price'], 2) ?></td>
                        <td>
                            <span class="badge <?= ($p['is_available'] ?? 1) ? 'bg-success' : 'bg-danger' ?>">
                                <?= ($p['is_available'] ?? 1) ? 'Available' : 'Unavailable' ?>
                            </span>
                        </td>
                        <td>
                            <a href="product_edit.php?id=<?= $p['product_id'] ?>" class="btn btn-sm btn-outline-light">Edit</a>
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