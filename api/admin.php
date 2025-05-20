<?php
include 'config.php';

if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: index.php");
    exit;
}
?>

<h1>Админ-панель</h1>
<p>Доступ только у админа!</p>

<ul>
    <li><a href="#">Добавить статью</a></li>
    <li><a href="#">Управление пользователями</a></li>
</ul>

<a href="index.php">На главную</a>