<?php
// Incluir funções necessárias
require_once __DIR__ . '/includes/functions.php';

// Obter conteúdo por slug
$slug = $_GET['slug'] ?? '';
$content = getContentBySlug($slug);

if (!$content) {
    header('Location: index.php');
    exit;
}

// Obter informações do capítulo
$chapter = getChapterInfo($content['chapter_number']);

if (!$chapter) {
    header('Location: index.php');
    exit;
}

// Configurações da página
$customTitle = $content['title'];
$pageHeading = $content['title'];
$pageDescription = $chapter['description'] ?? '';
$breadcrumb = generateBreadcrumb($chapter['number'], $content['title']);

// Obter próximo e anterior conteúdo
$nextContent = getNextContent($content['id'], $content['chapter_id']);
$prevContent = null; // Será implementado se necessário

// Verificar se está lido
$isRead = isRead($content['id']);

// Incluir header
include 'includes/header.php';
?>

<!-- Content Header -->
<div class="card mb-8">
    <div class="card-header">
        <div class="card-icon">
            <i class="fa-solid <?php echo htmlspecialchars($chapter['icon'] ?? 'fa-file'); ?>"></i>
        </div>
        <div>
            <h3 class="card-title"><?php echo htmlspecialchars($content['title']); ?></h3>
            <p class="card-description"><?php echo htmlspecialchars($chapter['title'] ?? ''); ?></p>
        </div>
    </div>
</div>

<!-- Content -->
<div class="prose max-w-none">
    <?php echo $content['content']; ?>
</div>

<!-- Fixed Footer -->
<div class="content-footer">
    <div class="content-footer-left">
        <form method="POST" class="inline">
            <input type="hidden" name="action" value="mark_read">
            <input type="hidden" name="content_id" value="<?php echo $content['id']; ?>">
            <button type="submit" class="btn <?php echo $isRead ? 'btn-secondary' : 'btn-primary'; ?>" <?php echo $isRead ? 'disabled' : ''; ?>>
                <i class="fa-solid fa-check mr-2"></i>
                Marcar como Lido
            </button>
        </form>

        <form method="POST" class="inline">
            <input type="hidden" name="action" value="unmark_read">
            <input type="hidden" name="content_id" value="<?php echo $content['id']; ?>">
            <button type="submit" class="btn <?php echo $isRead ? 'btn-primary' : 'btn-secondary'; ?>" <?php echo !$isRead ? 'disabled' : ''; ?>>
                <i class="fa-solid fa-times mr-2"></i>
                Desmarcar como Lido
            </button>
        </form>
    </div>

    <div class="content-footer-right">
        <?php if ($nextContent): ?>
            <a href="/content.php?slug=<?php echo $nextContent['slug']; ?>" class="btn btn-primary">
                Próxima Lição
                <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        <?php else: ?>
            <a href="/dashboard.php" class="btn btn-secondary">
                Ver Dashboard
                <i class="fa-solid fa-chart-line ml-2"></i>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php
// Incluir footer
include 'includes/footer.php';
?>