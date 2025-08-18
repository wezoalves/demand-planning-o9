<?php
// Incluir funções necessárias
require_once __DIR__ . '/includes/functions.php';

// Verificar se foi passado um número de capítulo
if (!isset($_GET['chapter']) || empty($_GET['chapter'])) {
    // Redirecionar para página inicial se não houver capítulo
    header('Location: index.php');
    exit;
}

$chapterNumber = (int)$_GET['chapter'];

// Obter informações do capítulo
$chapter = getChapterInfo($chapterNumber);

// Se não encontrar o capítulo, redirecionar para página inicial
if (!$chapter) {
    header('Location: index.php');
    exit;
}

$contents = getChapterContents($chapter['id']);
$progress = getChapterProgress($chapter['id']);

// Configurações da página
$customTitle = $chapter['title'];
$pageHeading = $chapter['title'];
$pageDescription = $chapter['description'];

// Incluir header
include 'includes/header.php';
?>

<!-- Chapter Header -->
<div class="hero">
    <div class="card-header">
        <div class="card-icon">
            <i class="fa-solid <?php echo $chapter['icon']; ?>"></i>
        </div>
        <div>
            <h1 class="hero-title"><?php echo htmlspecialchars($chapter['title']); ?></h1>
            <p class="hero-description"><?php echo htmlspecialchars($chapter['description']); ?></p>
        </div>
    </div>
</div>

<!-- Progress -->
<div class="progress-section">
    <div class="progress-header">
        <span class="progress-label">Progresso do Capítulo</span>
        <span class="progress-text"><?php echo $progress; ?>%</span>
    </div>
    <div class="progress-bar-container">
        <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
    </div>
</div>

<!-- Chapter Contents -->
<div class="section-header">
    <h3 class="section-title">Conteúdos do Capítulo</h3>
    <p class="section-description">Explore todos os conteúdos disponíveis neste capítulo.</p>
</div>

<div class="grid grid-cols-1">
    <?php foreach ($contents as $content): ?>
        <?php $isRead = isRead($content['id']); ?>
        <div class="card">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <?php if ($isRead): ?>
                        <i class="fa-solid fa-check-circle" style="color: hsl(var(--primary)); margin-right: 1rem; font-size: 1.25rem;"></i>
                    <?php else: ?>
                        <div style="width: 1.5rem; height: 1.5rem; border: 2px solid hsl(var(--border)); border-radius: 50%; margin-right: 1rem;"></div>
                    <?php endif; ?>

                    <div class="flex-1">
                        <h4 class="card-title">
                            <?php echo htmlspecialchars($content['title']); ?>
                        </h4>
                        <p class="card-description">
                            <?php
                            // Aqui você pode adicionar uma descrição curta do conteúdo
                            echo "Conteúdo sobre " . strtolower($content['title']);
                            ?>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <?php if ($isRead): ?>
                        <span class="text-sm font-medium" style="color: hsl(var(--primary));">Lido</span>
                    <?php endif; ?>
                    <a href="content.php?slug=<?php echo $content['slug']; ?>" class="btn btn-primary">
                        <?php echo $isRead ? 'Revisar' : 'Ler'; ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Chapter Summary -->
<div class="card mt-8">
    <h3 class="section-title">Resumo do Capítulo</h3>
    <p class="card-description">
        <?php echo htmlspecialchars($chapter['description']); ?>
    </p>

    <div class="flex items-center text-sm" style="color: hsl(var(--muted-foreground)); margin-top: 1rem;">
        <i class="fa-solid fa-clock mr-2"></i>
        <span>Tempo estimado de leitura: <?php echo count($contents) * 8; ?> minutos</span>
    </div>
</div>

<?php
// Incluir footer
include 'includes/footer.php';
?>