<?php
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>

<h1>Профиль</h1>
<p>Логин: <?= htmlspecialchars($_SESSION['user']['username']) ?></p>

<a href="index.php">На главную</a>