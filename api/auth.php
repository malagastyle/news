<?php
// Разрешаем CORS
header("Access-Control-Allow-Origin: *");
// Разрешаем только POST-запросы
header("Access-Control-Allow-Methods: POST");
// Разрешаем заголовок Content-Type
header("Access-Control-Allow-Headers: Content-Type");
// Ответ в формате JSON
header("Content-Type: application/json");

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    die(json_encode(['success' => false, 'message' => 'Метод не разрешён']));
}

// Получаем JSON-данные
$data = json_decode(file_get_contents('php://input'), true);

// Если данные не пришли — завершаем выполнение
if (!$data || empty($data['username']) || empty($data['password'])) {
    http_response_code(400); // Bad Request
    die(json_encode(['success' => false, 'message' => 'Не переданы логин или пароль']));
}

$username = trim($data['username']);
$password = trim($data['password']);

// Здесь добавьте свою логику аутентификации
// Например, проверка в базе данных

// Пример тестовой аутентификации
if ($username === 'admin' && $password === 'malagastyle') {
    session_start();
    $_SESSION['user'] = [
        'username' => $username,
        'is_admin' => true
    ];
    echo json_encode(['success' => true, 'message' => 'Успешный вход', 'is_admin' => true]);
} else {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Неверный логин или пароль']);
}
?>