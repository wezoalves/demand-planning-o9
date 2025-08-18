<?php
// Configurações da página
$customTitle = 'Página Inicial';
$pageHeading = 'o9 Learning Hub';
$pageDescription = 'Aprenda tudo sobre planejamento de demanda, gestão de fornecedores, IBP, RGM e o conceito revolucionário do Digital Brain através de conteúdos estruturados e estudos práticos.';

// Incluir header
include 'includes/header.php';

// Obter todos os capítulos
$chapters = getAllChapters();
?>

<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8 mb-8">
    <div class="max-w-4xl">
        <h2 class="text-3xl font-bold mb-3">o9 Learning Hub</h2>
        <p class="text-gray-600 mb-5 leading-relaxed">
            Aprenda tudo sobre planejamento de demanda, gestão de fornecedores, IBP, RGM e o conceito
            revolucionário do Digital Brain através de conteúdos estruturados e estudos práticos.
        </p>
        <!-- Feature badges -->
        <div class="flex flex-wrap gap-2 mb-6">
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">6 capítulos completos</span>
            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Conteúdo estruturado</span>
            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">Acompanhamento de progresso</span>
        </div>
    </div>
</div>

<!-- Featured Content -->
<div class="mb-8">
    <h3 class="text-2xl font-bold mb-4">Conteúdo em Destaque</h3>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <i class="fa-solid fa-chart-line text-blue-600 mr-3"></i>
                <h4 class="font-semibold mb-2">Fundamentos do Planejamento de Demanda</h4>
            </div>
            <p class="text-sm text-gray-600 flex-1">
                Entenda como equilibrar lead times operacionais com nível de serviço ao cliente através de
                previsões precisas e estrutura hierárquica.
            </p>
            <a href="fundamentos.php"
                class="mt-4 text-sm font-medium text-blue-700 hover:underline">Explorar conteúdo →</a>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <i class="fa-solid fa-handshake text-green-600 mr-3"></i>
                <h4 class="font-semibold mb-2">Gestão de Riscos com Fornecedores</h4>
            </div>
            <p class="text-sm text-gray-600 flex-1">
                Aprenda a mapear riscos em toda a cadeia de suprimentos, monitorar sinais de alerta e
                definir planos de mitigação eficazes.
            </p>
            <a href="gestao-riscos.php"
                class="mt-4 text-sm font-medium text-green-700 hover:underline">Explorar conteúdo →</a>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <i class="fa-solid fa-brain text-indigo-600 mr-3"></i>
                <h4 class="font-semibold mb-2">O Conceito do Digital Brain</h4>
            </div>
            <p class="text-sm text-gray-600 flex-1">
                Descubra como a plataforma o9 conecta dados, analytics e planejamento em tempo real para
                criar uma "mente digital" da empresa.
            </p>
            <a href="conceito-digital-brain.php"
                class="mt-4 text-sm font-medium text-indigo-700 hover:underline">Explorar conteúdo →</a>
        </div>
    </div>
</div>

<!-- Chapters -->
<div class="mb-8">
    <h3 class="text-2xl font-bold mb-4">Capítulos do Livro</h3>
    <div class="grid md:grid-cols-2 gap-6">
        <?php foreach ($chapters as $chapter): ?>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-3">
                    <i class="fa-solid <?php echo $chapter['icon']; ?> text-<?php echo $chapter['color']; ?>-600 mr-3"></i>
                    <h4 class="ml-3 font-semibold text-<?php echo $chapter['color']; ?>-700"><?php echo htmlspecialchars($chapter['title']); ?></h4>
                </div>
                <p class="text-sm text-<?php echo $chapter['color']; ?>-700 flex-1">
                    <?php echo htmlspecialchars($chapter['description']); ?>
                </p>
                <a href="chapter.php?chapter=<?php echo $chapter['number']; ?>"
                    class="mt-4 text-sm font-medium text-<?php echo $chapter['color']; ?>-700 hover:underline">
                    Explorar capítulo →
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
// Incluir footer
include 'includes/footer.php';
?>