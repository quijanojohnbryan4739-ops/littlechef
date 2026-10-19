<?php require 'config/db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Little Chef — Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<!-- Full Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🍣 Little Chef</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center gap-3">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="bulk_order.php">How to Order</a></li>
                <li class="nav-item"><a class="nav-link" href="order_status.php">Contact</a></li>
                <li class="nav-item"><a href="menu.php" class="btn btn-warning text-dark fw-bold px-3">Order Now</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Space -->
<div class="container my-5">
    <div class="row align-items-center bg-white p-5 rounded shadow-sm">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <h1 class="display-5 fw-bold text-dark">Freshly Crafted Sushi Delivered to Your Door</h1>
            <p class="lead text-muted my-3">Experience authentic Japanese flavors made with premium ingredients right here in Antipolo.</p>
            <a href="menu.php" class="btn btn-dark btn-lg px-4 mt-2">Explore Menu</a>
        </div>
        <div class="col-lg-6 text-center">
            <div class="bg-light border rounded p-5 text-muted">
                <h4>🍱 Featured Rolls & Platters</h4>
                <p class="small mb-0">Prepared fresh daily upon order confirmation.</p>
            </div>
        </div>
    </div>

    <!-- Key Benefits ("Why Little Chef?") -->
    <div class="row text-center my-5 g-4">
        <h2 class="fw-bold mb-4">Why Little Chef?</h2>
        <div class="col-md-4">
            <div class="card p-4 h-100 shadow-sm">
                <div class="display-6 fw-bold text-primary mb-2">1</div>
                <h5 class="fw-bold">Premium Fresh Ingredients</h5>
                <p class="text-muted small">Sourced fresh to ensure authentic taste and maximum quality in every bite.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 h-100 shadow-sm">
                <div class="display-6 fw-bold text-primary mb-2">2</div>
                <h5 class="fw-bold">Crafted by Experts</h5>
                <p class="text-muted small">Meticulously prepared rolls rolled to absolute perfection by skilled hands.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 h-100 shadow-sm">
                <div class="display-6 fw-bold text-primary mb-2">3</div>
                <h5 class="fw-bold">Fast & Reliable Service</h5>
                <p class="text-muted small">Quick pickup options or safe delivery right to your doorstep.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>