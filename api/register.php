<?php
require_once '../db.php';

// Включим максимальное логирование
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

file_put_contents('register.log', "\n\n".date('Y-m-d H:i:s')." - New registration attempt\n", FILE_APPEND);

try {
    // Получаем RAW данные
    $input = file_get_contents('php://input');
    file_put_contents('register.log', "Raw input: $input\n", FILE_APPEND);
    
    $data = json_decode($input, true);
    if (!$data) {
        throw new Exception('Invalid JSON: '.json_last_error_msg());
    }

    $username = trim($data['username'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    file_put_contents('register.log', "Data: ".print_r($data, true)."\n", FILE_APPEND);

    // Валидация
    if (empty($username)) throw new Exception('Username is required');
    if (empty($email)) throw new Exception('Email is required');
    if (empty($password)) throw new Exception('Password is required');
    if (strlen($password) < 6) throw new Exception('Password too short');

    // Проверка существования пользователя
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    
    if ($stmt->rowCount() > 0) {
        throw new Exception('User already exists');
    }

    // Хеширование пароля
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    file_put_contents('register.log', "Password hashed: $hashedPassword\n", FILE_APPEND);

    // Вставка пользователя
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, register_date) VALUES (?, ?, ?, CURDATE())");
    $result = $stmt->execute([$username, $email, $hashedPassword]);
    
    if (!$result) {
        $error = $stmt->errorInfo();
        throw new Exception('DB error: '.$error[2]);
    }

    $userId = $pdo->lastInsertId();
    file_put_contents('register.log', "User created with ID: $userId\n", FILE_APPEND);

    // Получаем данные пользователя
    $stmt = $pdo->prepare("SELECT id, username, email, register_date FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'user' => $user,
        'debug' => [
            'input' => $input,
            'raw_data' => $data
        ]
    ]);

} catch (Exception $e) {
    file_put_contents('register.log', "ERROR: ".$e->getMessage()."\n", FILE_APPEND);
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>