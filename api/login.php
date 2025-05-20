<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    if (empty($data['username']) || empty($data['password'])) {
        throw new Exception('Заполните все поля');
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$data['username']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($data['password'], $user['password'])) {
        throw new Exception('Неверный логин или пароль');
    }

    // Генерируем токен
    $token = bin2hex(random_bytes(32));
    $expires = time() + 3600 * 24; // 24 часа

    // Сохраняем токен в БД
    $stmt = $pdo->prepare("UPDATE users SET token = ?, token_expires = ? WHERE id = ?");
    $stmt->execute([$token, date('Y-m-d H:i:s', $expires), $user['id']]);

    echo json_encode([
        'success' => true,
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'is_admin' => $user['is_admin']
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>