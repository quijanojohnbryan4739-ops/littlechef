<?php
require_once __DIR__ . '/../config/db_connect.php';

$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Check if admin user already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user) {
    // Update the password hash for the existing admin
    $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
    $update->execute([$hash, $username]);
    echo "<h3>Success!</h3> Admin password has been updated to: <b>admin123</b>";
} else {
    // Insert a brand new admin user
    $insert = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'admin')");
    $insert->execute([$username, $hash]);
    echo "<h3>Success!</h3> Admin user created with password: <b>admin123</b>";
}

echo '<br><br><a href="login.php" style="padding: 10px 20px; background: #ffc107; color: #000; text-decoration: none; font-weight: bold; border-radius: 5px;">Proceed to Login Page</a>';
?>