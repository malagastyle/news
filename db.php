<?php
session_start();

$host = 'localhost';
$user = 'root'; // замените на ваши данные
$password = 'root'; // замените на ваши данные
$dbname = 'news_platform'; // имя вашей БД

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>