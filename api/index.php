<?php
session_start();
include 'config.php';

// Проверяем, залогинен ли пользователь
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = null;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

<!-- Динамическое отображение кнопок -->
<div id="auth-buttons">
    <?php if ($user): ?>
        <?php if ($user['is_admin']): ?>
            <a href="admin.php">Админ-панель</a>
        <?php endif; ?>
        <a href="profile.php">Профиль</a>
        <a href="logout.php">Выход</a>
    <?php else: ?>
        <a href="login.php">Войти</a>
        <a href="register.php">Зарегистрироваться</a>
    <?php endif; ?>
</div>

<h1>Добро пожаловать!</h1>

</body>
</html>