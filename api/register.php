<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $is_admin = 0;

    $sql = "INSERT INTO users (username, password, is_admin) VALUES ('$username', '$password', $is_admin)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Регистрация успешна! <a href='login.php'>Войти</a>";
    } else {
        echo "Ошибка регистрации: " . $conn->error;
    }
}
?>

<form method="post">
    Логин: <input type="text" name="username" required><br>
    Пароль: <input type="password" name="password" required><br>
    <button type="submit">Зарегистрироваться</button>
</form>