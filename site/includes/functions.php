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
    // Tentar recuperar do cookie primeiro
    if (isset($_COOKIE['o9_user_id'])) {
        $_SESSION['session_id'] = $_COOKIE['o9_user_id'];
    } else {
        $_SESSION['session_id'] = uniqid('user_', true);
        // Salvar no cookie por 1 ano
        setcookie('o9_user_id', $_SESSION['session_id'], time() + (365 * 24 * 60 * 60), '/');
    }
}

$db = Database::getInstance();

/**
 * Marcar conteúdo como lido
 */
function markAsRead($contentId)
{
    global $db;
    $sessionId = $_SESSION['session_id'];
    $result = $db->markAsRead($sessionId, $contentId);

    if ($result) {
        // Salvar no localStorage via JavaScript
        echo "<script>
            if (typeof localStorage !== 'undefined') {
                let progress = JSON.parse(localStorage.getItem('o9_progress') || '{}');
                progress['content_" . (string)$contentId . "'] = {
                    read: true,
                    timestamp: new Date().toISOString()
                };
                localStorage.setItem('o9_progress', JSON.stringify(progress));
            }
        </script>";
    }

    return $result;
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
    $result = $db->unmarkAsRead($sessionId, $contentId);

    if ($result) {
        // Remover do localStorage via JavaScript
        echo "<script>
            if (typeof localStorage !== 'undefined') {
                let progress = JSON.parse(localStorage.getItem('o9_progress') || '{}');
                delete progress['content_" . (string)$contentId . "'];
                localStorage.setItem('o9_progress', JSON.stringify(progress));
            }
        </script>";
    }

    return $result;
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
 * Obter conteúdo por ID
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
 * Obter próximo conteúdo
 */
function getNextContent($currentContentId, $chapterId)
{
    global $db;
    return $db->getNextContent($currentContentId, $chapterId);
}

/**
 * Obter últimos conteúdos lidos
 */
function getRecentReads($limit = 5)
{
    global $db;
    return $db->getRecentReads($_SESSION['session_id'], $limit);
}

/**
 * Obter próximo conteúdo não lido
 */
function getNextUnreadContent()
{
    global $db;
    return $db->getNextUnreadContent($_SESSION['session_id']);
}

/**
 * Sincronizar progresso do localStorage com o banco
 */
function syncProgressFromLocalStorage()
{
    echo "<script>
        if (typeof localStorage !== 'undefined') {
            const progress = JSON.parse(localStorage.getItem('o9_progress') || '{}');
            const contentIds = Object.keys(progress).filter(key => key.startsWith('content_'));
            
            if (contentIds.length > 0) {
                // Enviar progresso para sincronizar com o banco
                fetch('sync_progress.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        progress: progress,
                        contentIds: contentIds.map(id => id.replace('content_', ''))
                    })
                });
            }
        }
    </script>";
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
            $breadcrumb[] = '<a href="chapter.php?chapter=' . $chapterNumber . '">' . $chapterInfo['title'] . '</a>';
        }
    }

    if ($contentTitle) {
        $breadcrumb[] = '<span style="color: hsl(var(--muted-foreground));">' . $contentTitle . '</span>';
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

    // Obter página atual
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');

    // Grupo principal - Ferramentas (movido para cima)
    $html .= '<div class="sidebar-group">';
    $html .= '<div class="sidebar-group-label">Ferramentas</div>';
    $html .= '<div class="sidebar-group-content">';

    $html .= '<a href="index.php" class="nav-item' . ($currentPage == 'index' ? ' active' : '') . '" title="Início">';
    $html .= '<i class="fa-solid fa-home"></i>';
    $html .= '<span class="nav-text">Início</span>';
    $html .= '</a>';

    $html .= '<a href="dashboard.php" class="nav-item' . ($currentPage == 'dashboard' ? ' active' : '') . '" title="Dashboard">';
    $html .= '<i class="fa-solid fa-chart-line"></i>';
    $html .= '<span class="nav-text">Dashboard</span>';
    $html .= '</a>';

    $html .= '<a href="#" class="nav-item" title="Histórico">';
    $html .= '<i class="fa-solid fa-history"></i>';
    $html .= '<span class="nav-text">Histórico</span>';
    $html .= '</a>';

    $html .= '</div>';
    $html .= '</div>';

    // Grupo secundário - Capítulos (movido para baixo)
    $html .= '<div class="sidebar-group">';
    $html .= '<div class="sidebar-group-label">Módulos de Estudo</div>';
    $html .= '<div class="sidebar-group-content">';

    foreach ($chapters as $chapter) {
        $progress = getChapterProgress($chapter['id']);
        $contents = getChapterContents($chapter['id']);

        // Verificar se é o capítulo ativo (por número ou por conteúdo)
        $isActive = false;
        if (isset($_GET['chapter']) && $_GET['chapter'] == $chapter['number']) {
            $isActive = true;
        } elseif (isset($_GET['slug'])) {
            // Verificar se o slug atual pertence a este capítulo
            foreach ($contents as $content) {
                if ($_GET['slug'] == $content['slug']) {
                    $isActive = true;
                    break;
                }
            }
        }

        $html .= '<div class="nav-item' . ($isActive ? ' active' : '') . '" data-chapter="' . $chapter['number'] . '" title="' . htmlspecialchars($chapter['title']) . '">';
        $html .= '<i class="fa-solid ' . $chapter['icon'] . '"></i>';
        $html .= '<span class="nav-text">' . $chapter['title'] . '</span>';
        $html .= '<i class="fa-solid fa-chevron-right nav-chevron"></i>';
        $html .= '</div>';

        // Submenu para os conteúdos do capítulo (fechado por padrão)
        $html .= '<div class="nav-submenu collapsed" id="submenu-' . $chapter['number'] . '">';
        foreach ($contents as $content) {
            $isRead = isRead($content['id']);
            $isContentActive = isset($_GET['slug']) && $_GET['slug'] == $content['slug'];

            $html .= '<a href="content.php?slug=' . $content['slug'] . '" class="nav-submenu-item' . ($isContentActive ? ' active' : '') . '" title="' . htmlspecialchars($content['title']) . '">';
            if ($isRead) {
                $html .= '<i class="fa-solid fa-check-circle" style="color: hsl(var(--sidebar-primary));"></i>';
            } else {
                $html .= '<i class="fa-solid fa-circle"></i>';
            }
            $html .= '<span class="submenu-text">' . $content['title'] . '</span>';
            $html .= '</a>';
        }
        $html .= '</div>';
    }

    $html .= '</div>';
    $html .= '</div>';

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
            <div class="alert alert-success">
                <p class="font-medium">
                    <i class="fa-solid fa-check-circle mr-2"></i>
                    Conteúdo marcado como lido
                </p>
            </div>
            <form method="POST" class="inline">
                <input type="hidden" name="action" value="unmark_read">
                <input type="hidden" name="content_id" value="' . $contentId . '">
                <button type="submit" class="btn btn-secondary">
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
                <button type="submit" class="btn btn-primary">
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
function processReadActions()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action']) && isset($_POST['content_id'])) {
            $contentId = (int)$_POST['content_id'];

            if ($_POST['action'] === 'mark_read') {
                markAsRead($contentId);
            } elseif ($_POST['action'] === 'unmark_read') {
                unmarkAsRead($contentId);
            }

            // Redirecionar para evitar reenvio do formulário
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }
}

// Processar ações
processReadActions();

// Sincronizar progresso do localStorage
syncProgressFromLocalStorage();
