<?php
// Arquivo para sincronizar progresso do localStorage com o banco
require_once __DIR__ . '/includes/functions.php';

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Obter dados JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['contentIds'])) {
    http_response_code(400);
    exit('Invalid data');
}

$sessionId = $_SESSION['session_id'];
$contentIds = $input['contentIds'];

// Marcar conteúdos como lidos no banco
foreach ($contentIds as $contentId) {
    $db->markAsRead($sessionId, (int)$contentId);
}

// Retornar sucesso
http_response_code(200);
echo json_encode(['success' => true, 'synced' => count($contentIds)]);
