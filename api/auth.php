<?php
include 'db.php';

session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Проверка админских учетных данных
if ($username === 'admin' && $password === 'malagastyle') {
    $_SESSION['user'] = [
        'username' => 'admin',
        'is_admin' => true
    ];
    header('Location: admin-panel.html');
    exit;
}

// Проверка обычных пользователей из БД
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'username' => $user['username'],
        'is_admin' => false
    ];
    header('Location: profile.html');
    exit;
}

// Если аутентификация не удалась
header('Location: login.html?error=1');