<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="style.css">
=======
    <link rel="stylesheet" href="css/style.css">
>>>>>>> 42b84d3269b121dcbccbbe9dbe47458d3b86c3e6
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="profile-container">
        <h1>Профиль: <?= htmlspecialchars($user['username']) ?></h1>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
        <p>Дата регистрации: <?= $user['created_at'] ?></p>
        
        <?php if ($user['is_admin'] == 1): ?>
            <div class="admin-panel">
                <h2>Админ-панель</h2>
                <a href="admin/news.php">Управление новостями</a>
                <a href="admin/users.php">Управление пользователями</a>
            </div>
        <?php endif; ?>
        
        <a href="edit_profile.php">Редактировать</a>
        <a href="logout.php">Выйти</a>
    </div>
    
<<<<<<< HEAD
   <?php include 'footer.php'; ?>
=======
    <?php include 'footer.php'; ?>
>>>>>>> 42b84d3269b121dcbccbbe9dbe47458d3b86c3e6
</body>
</html>