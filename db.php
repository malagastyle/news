<?php
session_start();

<<<<<<< HEAD
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
=======
$host = 'localhost';
$user = 'root'; // замените на ваши данные
$password = 'root'; // замените на ваши данные
$dbname = 'news_platform'; // имя вашей БД

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
>>>>>>> 42b84d3269b121dcbccbbe9dbe47458d3b86c3e6
}
?>