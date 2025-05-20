<?php
include '../../config.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = $conn->real_escape_string($data['username']);
$password = $data['password'];

$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        echo json_encode([
            'success' => true,
            'token' => bin2hex(random_bytes(50)), // или JWT
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'is_admin' => $user['is_admin']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
}
?>