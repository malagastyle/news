<?php
require_once '../db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$title = $data['title'] ?? '';
$content = $data['content'] ?? '';

try {
    // In a real app, you would get author_id from session
    $stmt = $pdo->prepare("INSERT INTO articles (title, content, author_id, release_date, category_id) VALUES (?, ?, 1, CURDATE(), 1)");
    $stmt->execute([$title, $content]);
    
    echo json_encode(['success' => true, 'message' => 'Новость добавлена']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>