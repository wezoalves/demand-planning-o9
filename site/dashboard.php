<?php
// Incluir funções necessárias
require_once __DIR__ . '/includes/functions.php';

// Obter dados para o dashboard
$chapters = getAllChapters();
$overallProgress = getOverallProgress();
$readCount = $db->getProgress($_SESSION['session_id']);
$totalContent = 35;

// Calcular estatísticas por módulo
$chapterStats = [];
foreach ($chapters as $chapter) {
    $progress = getChapterProgress($chapter['id']);
    $contents = getChapterContents($chapter['id']);
    $readInChapter = 0;

    foreach ($contents as $content) {
        if (isRead($content['id'])) {
            $readInChapter++;
        }
    }

    $chapterStats[] = [
        'chapter' => $chapter,
        'progress' => $progress,
        'read' => $readInChapter,
        'total' => count($contents)
    ];
}

// Configurações da página
$customTitle = 'Dashboard';
$pageHeading = 'Dashboard de Aprendizado';
$pageDescription = 'Acompanhe sua evolução e progresso no aprendizado sobre planejamento de demanda e gestão de fornecedores.';

// Incluir header
include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="hero">
    <h2 class="hero-title">Dashboard de Aprendizado</h2>
    <p class="hero-description">
        Acompanhe sua evolução e progresso no aprendizado sobre planejamento de demanda e gestão de fornecedores.
    </p>
</div>

<!-- Progress Overview -->
<div class="grid grid-cols-3 mb-8">
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <h4 class="card-title">Conteúdos Lidos</h4>
        </div>
        <div class="text-3xl font-bold mb-2" style="color: hsl(var(--primary));"><?php echo $readCount; ?></div>
        <p class="card-description">de <?php echo $totalContent; ?> conteúdos disponíveis</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-percentage"></i>
            </div>
            <h4 class="card-title">Progresso Geral</h4>
        </div>
        <div class="text-3xl font-bold mb-2" style="color: hsl(var(--primary));"><?php echo $overallProgress; ?>%</div>
        <p class="card-description">concluído do curso completo</p>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <h4 class="card-title">Módulos Concluídos</h4>
        </div>
        <div class="text-3xl font-bold mb-2" style="color: hsl(var(--primary));">
            <?php
            $completedChapters = 0;
            foreach ($chapterStats as $stat) {
                if ($stat['progress'] == 100) {
                    $completedChapters++;
                }
            }
            echo $completedChapters;
            ?>
        </div>
        <p class="card-description">de <?php echo count($chapters); ?> Módulos</p>
    </div>
</div>

<!-- Chapter Progress -->
<div class="section-header">
    <h3 class="section-title">Progresso por módulo</h3>
    <p class="section-description">Veja seu progresso detalhado em cada módulo do curso.</p>
</div>

<div class="grid grid-cols-2">
    <?php foreach ($chapterStats as $stat): ?>
        <div class="card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa-solid <?php echo $stat['chapter']['icon']; ?>"></i>
                </div>
                <div class="flex-1">
                    <h4 class="card-title"><?php echo htmlspecialchars($stat['chapter']['title']); ?></h4>
                    <p class="card-description"><?php echo $stat['read']; ?> de <?php echo $stat['total']; ?> conteúdos lidos</p>
                </div>
                <div class="text-right ml-auto">
                    <div class="text-2xl font-bold" style="color: hsl(var(--primary));"><?php echo $stat['progress']; ?>%</div>
                </div>
            </div>

            <div class="progress-bar-container mb-4">
                <div class="progress-bar" style="width: <?php echo $stat['progress']; ?>%"></div>
            </div>

            <?php if ($stat['progress'] < 100): ?>
                <a href="chapter.php?chapter=<?php echo $stat['chapter']['number']; ?>" class="card-link">
                    Continuar módulo →
                </a>
            <?php else: ?>
                <div class="flex items-center text-sm" style="color: hsl(var(--primary));">
                    <i class="fa-solid fa-check-circle mr-2"></i>
                    módulo concluído!
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- Recent Activity -->
<div class="section-header mt-8">
    <h3 class="section-title">Atividade Recente</h3>
    <p class="section-description">Seus últimos conteúdos lidos e próximos passos recomendados.</p>
</div>

<div class="card">
    <div class="grid grid-cols-2 gap-8">
        <div>
            <h4 class="section-title mb-4">Últimos Conteúdos Lidos</h4>
            <?php
            // Buscar últimos conteúdos lidos
            $recentReads = $db->getRecentReads($_SESSION['session_id'], 5);
            if ($recentReads): ?>
                <div class="space-y-3">
                    <?php foreach ($recentReads as $read): ?>
                        <div class="flex items-center rounded-lg" style="background-color: hsl(var(--muted));">
                            <div class="dashboard-icon">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium"><?php echo htmlspecialchars($read['title']); ?></div>
                                <div class="text-sm" style="color: hsl(var(--muted-foreground));">
                                    módulo <?php echo $read['chapter_number']; ?> - <?php echo htmlspecialchars($read['chapter_title']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-sm" style="color: hsl(var(--muted-foreground));">Nenhum conteúdo lido ainda. Comece sua jornada de aprendizado!</p>
            <?php endif; ?>
        </div>

        <div>
            <h4 class="section-title mb-4">Próximos Passos</h4>
            <?php
            // Encontrar próximo conteúdo não lido
            $nextContent = $db->getNextUnreadContent($_SESSION['session_id']);
            if ($nextContent): ?>
                <div class="p-4 rounded-lg border" style="border-color: hsl(var(--border));">
                    <h5 class="font-semibold mb-2"><?php echo htmlspecialchars($nextContent['title']); ?></h5>
                    <p class="text-sm mb-3" style="color: hsl(var(--muted-foreground));">
                        módulo <?php echo $nextContent['chapter_number']; ?> - <?php echo htmlspecialchars($nextContent['chapter_title']); ?>
                    </p>
                    <a href="content.php?slug=<?php echo $nextContent['slug']; ?>" class="btn btn-primary">
                        Continuar Aprendendo
                    </a>
                </div>
            <?php else: ?>
                <div class="p-4 rounded-lg border" style="border-color: hsl(var(--border));">
                    <h5 class="font-semibold mb-2">Parabéns!</h5>
                    <p class="text-sm mb-3" style="color: hsl(var(--muted-foreground));">
                        Você concluiu todos os conteúdos disponíveis.
                    </p>
                    <a href="index.php" class="btn btn-secondary">
                        Revisar Conteúdos
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Incluir footer
include 'includes/footer.php';
?>