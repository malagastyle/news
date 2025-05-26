<?php
session_start();
<<<<<<< HEAD
require_once 'api/db.php';

// Если пользователь уже авторизован, перенаправляем
if (isset($_SESSION['user'])) {
    header('Location: ' . ($_SESSION['user']['is_admin'] ? 'admin-panel.html' : 'profile.html'));
    exit;
}

$error = null;

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        // Проверка админских учетных данных
        if ($username === 'admin' && $password === 'malagastyle') {
            $_SESSION['user'] = [
                'username' => 'admin',
                'is_admin' => true
            ];
            header('Location: admin-panel.html');
            exit;
        }
        
        // Проверка обычных пользователей
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'is_admin' => false
            ];
            header('Location: profile.html');
            exit;
        } else {
            $error = "Неверный логин или пароль";
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $error = "Ошибка сервера. Пожалуйста, попробуйте позже.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h1><i class="fas fa-sign-in-alt"></i> Вход в систему</h1>
        </div>

        <?php if ($error): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Логин:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="auth-btn login-btn">
                <i class="fas fa-sign-in-alt"></i> Войти
            </button>
        </form>

        <div class="auth-footer">
            Нет аккаунта? <a href="register.html">Зарегистрируйтесь</a>
        </div>
    </div>
</body>
</html>
=======
require_once 'db.php';

if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: profile.php');
        exit;
    } else {
        $error = "Неверный email или пароль";
    }
}
?>
>>>>>>> 42b84d3269b121dcbccbbe9dbe47458d3b86c3e6
