<?php

/**
 * Funções utilitárias para o o9 Learning Hub
 */

// Incluir configuração do banco de dados
require_once __DIR__ . '/../config/database.php';

// Iniciar sessão se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Gerar ID de sessão único se não existir
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = uniqid('user_', true);
}

$db = Database::getInstance();

/**
 * Marcar conteúdo como lido
 */
function markAsRead($contentId)
{
    global $db;
    $sessionId = $_SESSION['session_id'];
    return $db->markAsRead($sessionId, $contentId);
}

/**
 * Verificar se conteúdo foi lido
 */
function isRead($contentId)
{
    global $db;
    $sessionId = $_SESSION['session_id'];
    return $db->isRead($sessionId, $contentId);
}

/**
 * Desmarcar conteúdo como lido
 */
function unmarkAsRead($contentId)
{
    global $db;
    $sessionId = $_SESSION['session_id'];
    return $db->unmarkAsRead($sessionId, $contentId);
}

/**
 * Obter progresso geral
 */
function getOverallProgress()
{
    global $db;
    $sessionId = $_SESSION['session_id'];
    $readCount = $db->getProgress($sessionId);
    $totalContent = 35; // Total de conteúdos no site
    return round(($readCount / $totalContent) * 100);
}

/**
 * Obter progresso por capítulo
 */
function getChapterProgress($chapterId)
{
    global $db;
    $sessionId = $_SESSION['session_id'];
    return $db->getChapterProgress($sessionId, $chapterId);
}

/**
 * Obter lista de capítulos
 */
function getAllChapters()
{
    global $db;
    return $db->getAllChapters();
}

/**
 * Obter informações do capítulo
 */
function getChapterInfo($chapterNumber)
{
    global $db;
    return $db->getChapterByNumber($chapterNumber);
}

/**
 * Obter lista de conteúdos por capítulo
 */
function getChapterContents($chapterId)
{
    global $db;
    return $db->getChapterContents($chapterId);
}

/**
 * Obter conteúdo específico
 */
function getContent($contentId)
{
    global $db;
    return $db->getContent($contentId);
}

/**
 * Obter conteúdo por slug
 */
function getContentBySlug($slug)
{
    global $db;
    return $db->getContentBySlug($slug);
}

/**
 * Buscar conteúdos
 */
function searchContents($query)
{
    global $db;
    return $db->searchContents($query);
}

/**
 * Obter próximo conteúdo na sequência
 */
function getNextContent($currentContentId, $chapterId)
{
    global $db;
    return $db->getNextContent($currentContentId, $chapterId);
}

/**
 * Processar ações de marcação/desmarcação
 */
function processReadActions()
{
    // Não processar ações do admin
    if (basename($_SERVER['SCRIPT_NAME']) === 'admin.php') {
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'mark_read':
                    if (isset($_POST['content_id'])) {
                        markAsRead($_POST['content_id']);
                    }
                    break;
                case 'unmark_read':
                    if (isset($_POST['content_id'])) {
                        unmarkAsRead($_POST['content_id']);
                    }
                    break;
                case 'clear_all':
                    // Limpar todo o progresso da sessão atual
                    global $db;
                    $sessionId = $_SESSION['session_id'];
                    $db->getConnection()->prepare("DELETE FROM user_progress WHERE session_id = ?")->execute([$sessionId]);
                    break;
            }

            // Redirecionar para evitar reenvio do formulário
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }
}

/**
 * Gerar breadcrumb
 */
function generateBreadcrumb($chapterNumber = null, $contentTitle = null)
{
    $breadcrumb = ['<a href="index.php">Início</a>'];

    if ($chapterNumber) {
        $chapterInfo = getChapterInfo($chapterNumber);
        if ($chapterInfo) {
            $breadcrumb[] = '<a href="capitulo' . $chapterNumber . '.php">' . $chapterInfo['title'] . '</a>';
        }
    }

    if ($contentTitle) {
        $breadcrumb[] = '<span class="text-gray-500">' . $contentTitle . '</span>';
    }

    return implode(' > ', $breadcrumb);
}

/**
 * Gerar menu de navegação
 */
function generateNavigation()
{
    $chapters = getAllChapters();
    $html = '';

    foreach ($chapters as $chapter) {
        $progress = getChapterProgress($chapter['id']);
        $contents = getChapterContents($chapter['id']);

        $html .= '<div class="chapter-group">';
        $html .= '<a href="chapter.php?chapter=' . $chapter['number'] . '" class="flex items-center px-3 py-2 rounded-lg hover:bg-' . $chapter['color'] . '-50 text-gray-700 hover:text-' . $chapter['color'] . '-700 transition-colors">';
        $html .= '<i class="fa-solid ' . $chapter['icon'] . ' mr-3 text-' . $chapter['color'] . '-600"></i>';
        $html .= '<span class="font-medium">' . $chapter['title'] . '</span>';
        $html .= '<i class="fa-solid fa-chevron-down ml-auto text-gray-400 text-xs"></i>';
        $html .= '</a>';

        $html .= '<div class="chapter-submenu ml-6 mt-2 space-y-1">';
        foreach ($contents as $content) {
            $isRead = isRead($content['id']);
            $readClass = $isRead ? 'text-green-600' : 'text-gray-600';
            $readIcon = $isRead ? '<i class="fa-solid fa-check-circle mr-2 text-green-500"></i>' : '';

            $html .= '<a href="content.php?slug=' . $content['slug'] . '" class="block px-3 py-1 text-sm ' . $readClass . ' hover:text-' . $chapter['color'] . '-600 hover:bg-' . $chapter['color'] . '-50 rounded">';
            $html .= $readIcon . $content['title'];
            $html .= '</a>';
        }
        $html .= '</div>';
        $html .= '</div>';
    }

    return $html;
}

/**
 * Gerar botão de marcação como lido
 */
function generateReadButton($contentId, $contentTitle)
{
    $isRead = isRead($contentId);

    if ($isRead) {
        return '
        <div class="mt-8 text-center">
            <div class="bg-green-100 border border-green-200 rounded-lg p-4 mb-4">
                <p class="text-green-800 font-medium">
                    <i class="fa-solid fa-check-circle mr-2"></i>
                    Conteúdo marcado como lido
                </p>
            </div>
            <form method="POST" class="inline">
                <input type="hidden" name="action" value="unmark_read">
                <input type="hidden" name="content_id" value="' . $contentId . '">
                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center mx-auto">
                    <i class="fa-solid fa-times mr-2"></i>
                    Desmarcar como Lido
                </button>
            </form>
        </div>';
    } else {
        return '
        <div class="mt-8 text-center">
            <form method="POST" class="inline">
                <input type="hidden" name="action" value="mark_read">
                <input type="hidden" name="content_id" value="' . $contentId . '">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center mx-auto">
                    <i class="fa-solid fa-check mr-2"></i>
                    Marcar como Lido
                </button>
            </form>
        </div>';
    }
}

/**
 * Processar ações na página atual
 */
processReadActions();
