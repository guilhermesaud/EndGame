<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if (!isset($_GET['login'])) {
    echo json_encode(['error' => 'Login não fornecido']);
    exit;
}

$login = trim($_GET['login']);

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE login = ?");
    $stmt->execute([$login]);
    $count = $stmt->fetchColumn();
    
    echo json_encode([
        'exists' => $count > 0,
        'login' => $login
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro no banco de dados']);
}