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
<div class="bg-gradient-to-r from-<?php echo $chapter['color']; ?>-50 to-<?php echo $chapter['color']; ?>-100 rounded-xl p-6 mb-8">
    <div class="flex items-center mb-4">
        <i class="fa-solid <?php echo $chapter['icon']; ?> text-<?php echo $chapter['color']; ?>-600 text-3xl mr-4"></i>
        <div>
            <h1 class="text-3xl font-bold text-<?php echo $chapter['color']; ?>-800"><?php echo htmlspecialchars($chapter['title']); ?></h1>
            <p class="text-<?php echo $chapter['color']; ?>-700 mt-2"><?php echo htmlspecialchars($chapter['description']); ?></p>
        </div>
    </div>
</div>

<!-- Progress -->
<div class="mt-6">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-<?php echo $chapter['color']; ?>-800">Progresso do Capítulo</span>
        <span class="text-sm text-<?php echo $chapter['color']; ?>-600"><?php echo $progress; ?>%</span>
    </div>
    <div class="w-full bg-<?php echo $chapter['color']; ?>-200 rounded-full h-3">
        <div class="bg-<?php echo $chapter['color']; ?>-600 h-3 rounded-full transition-all duration-300"
            style="width: <?php echo $progress; ?>%"></div>
    </div>
</div>

<!-- Chapter Contents -->
<div class="mb-8">
    <h3 class="text-2xl font-bold mb-6">Conteúdos do Capítulo</h3>
    <div class="grid gap-4">
        <?php foreach ($contents as $content): ?>
            <?php $isRead = isRead($content['id']); ?>
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <?php if ($isRead): ?>
                            <i class="fa-solid fa-check-circle text-green-500 mr-4 text-xl"></i>
                        <?php else: ?>
                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-4"></div>
                        <?php endif; ?>

                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($content['title']); ?>
                            </h4>
                            <p class="text-gray-600 text-sm">
                                <?php
                                // Aqui você pode adicionar uma descrição curta do conteúdo
                                echo "Conteúdo sobre " . strtolower($content['title']);
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <?php if ($isRead): ?>
                            <span class="text-sm text-green-600 font-medium">Lido</span>
                        <?php endif; ?>
                        <a href="content.php?slug=<?php echo $content['slug']; ?>"
                            class="bg-<?php echo $chapter['color']; ?>-500 hover:bg-<?php echo $chapter['color']; ?>-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <?php echo $isRead ? 'Revisar' : 'Ler'; ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Chapter Summary -->
<div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
    <h3 class="text-xl font-semibold mb-4">Resumo do Capítulo</h3>
    <p class="text-gray-700 leading-relaxed">
        <?php echo htmlspecialchars($chapter['description']); ?>
    </p>

    <div class="mt-4 flex items-center text-sm text-gray-600">
        <i class="fa-solid fa-clock mr-2"></i>
        <span>Tempo estimado de leitura: <?php echo count($contents) * 8; ?> minutos</span>
    </div>
</div>

<?php
// Incluir footer
include 'includes/footer.php';
?>