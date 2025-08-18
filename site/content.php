<?php
// Incluir funções necessárias
require_once __DIR__ . '/includes/functions.php';

// Verificar se foi passado um slug
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    // Redirecionar para página inicial se não houver slug
    header('Location: index.php');
    exit;
}

$slug = $_GET['slug'];

// Obter conteúdo pelo slug
$content = getContentBySlug($slug);

// Se não encontrar o conteúdo, redirecionar para página inicial
if (!$content) {
    header('Location: index.php');
    exit;
}

$chapter = getChapterInfo($content['chapter_number']);

// Configurações da página
$customTitle = $content['title'];
$pageHeading = $content['title'];
$pageDescription = 'Conteúdo sobre ' . strtolower($content['title']);
$breadcrumb = generateBreadcrumb($content['chapter_number'], $content['title']);

// Incluir header
include 'includes/header.php';
?>

<!-- Content Header -->
<div class="bg-gradient-to-r from-<?php echo $chapter['color']; ?>-50 to-<?php echo $chapter['color']; ?>-100 rounded-xl p-6 mb-8">
    <div class="flex items-center mb-4">
        <i class="fa-solid <?php echo $chapter['icon']; ?> text-<?php echo $chapter['color']; ?>-600 text-2xl mr-4"></i>
        <div>
            <h2 class="text-2xl font-bold text-<?php echo $chapter['color']; ?>-800"><?php echo htmlspecialchars($content['title']); ?></h2>
            <p class="text-<?php echo $chapter['color']; ?>-700 mt-1">Capítulo <?php echo $content['chapter_number']; ?> - <?php echo htmlspecialchars($chapter['title']); ?></p>
        </div>
    </div>
</div>

<!-- Content -->
<div class="prose prose-lg max-w-none">
    <?php echo $content['content']; ?>

    <?php
    // Gerar link para próximo conteúdo se existir
    $nextContent = getNextContent($content['id'], $content['chapter_id']);
    if ($nextContent): ?>
        <div class="bg-<?php echo $chapter['color']; ?>-50 border border-<?php echo $chapter['color']; ?>-200 rounded-lg p-6 mt-8">
            <h3 class="text-xl font-semibold mb-3 text-<?php echo $chapter['color']; ?>-800">Próximos Passos</h3>
            <p class="text-<?php echo $chapter['color']; ?>-700 mb-4">
                Continue sua jornada de aprendizado com o próximo conteúdo.
            </p>
            <a href="content.php?slug=<?php echo $nextContent['slug']; ?>"
                class="inline-flex items-center text-<?php echo $chapter['color']; ?>-600 hover:text-<?php echo $chapter['color']; ?>-800 font-medium">
                Continuar para <?php echo htmlspecialchars($nextContent['title']); ?> →
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Read Button -->
<?php echo generateReadButton($content['id'], $content['title']); ?>

<?php
// Incluir footer
include 'includes/footer.php';
?>