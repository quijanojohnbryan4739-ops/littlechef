<?php
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) { 
    header("Location: dashboard.php"); 
    exit; 
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['user_id'];
        $_SESSION['admin_user'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Little Chef</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0b0b0b; color: #f8f9fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background-color: #161616; border: 1px solid #332222; color: #fff; border-radius: 0.75rem; }
        .form-control { background-color: #1a1a1a; color: #fff; border: 1px solid #442222; }
        .form-control:focus { background-color: #1a1a1a; color: #fff; border-color: #ffc107; }
    </style>
</head>
<body>
<div class="container" style="max-width:420px">
    <h2 class="text-center mb-4 text-warning fw-bold">🍣 Little Chef — Admin</h2>
    <?php if ($error): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-warning w-100 fw-bold py-2 mb-2">Login</button>
        <a href="../index.php" class="btn btn-outline-light w-100 btn-sm">← Back to Shop</a>
    </form>
</div>
</body>
</html>