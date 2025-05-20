<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $bio = $_POST['bio'];
    
    $stmt = $pdo->prepare("UPDATE users SET username = ?, bio = ? WHERE id = ?");
    $stmt->execute([$username, $bio, $user['id']]);
    
    $_SESSION['user']['username'] = $username;
    header('Location: profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование профиля</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <form method="POST">
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        <textarea name="bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
        <button type="submit">Сохранить</button>
    </form>
    
    <?php include 'footer.php'; ?>
</body>
</html>