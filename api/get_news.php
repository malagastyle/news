<?php
require_once '../db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM articles ORDER BY release_date DESC LIMIT 5");
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($news);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>