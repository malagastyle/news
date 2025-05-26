<?php
<<<<<<< HEAD
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
=======
include 'db.php';

session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Проверка админских учетных данных
if ($username === 'admin' && $password === 'malagastyle') {
    $_SESSION['user'] = [
        'username' => 'admin',
        'is_admin' => true
    ];
    header('Location: admin-panel.html');
    exit;
}

// Проверка обычных пользователей из БД
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'username' => $user['username'],
        'is_admin' => false
    ];
    header('Location: profile.html');
    exit;
}

// Если аутентификация не удалась
header('Location: login.html?error=1');
>>>>>>> 42b84d3269b121dcbccbbe9dbe47458d3b86c3e6
