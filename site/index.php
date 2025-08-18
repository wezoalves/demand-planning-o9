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
<div class="hero">
    <h2 class="hero-title">o9 Learning Hub</h2>
    <p class="hero-description">
        Aprenda tudo sobre planejamento de demanda, gestão de fornecedores, IBP, RGM e o conceito
        revolucionário do Digital Brain através de conteúdos estruturados e estudos práticos.
    </p>
    <!-- Feature badges -->
    <div class="badge-container">
        <span class="badge">6 capítulos completos</span>
        <span class="badge">Conteúdo estruturado</span>
        <span class="badge">Acompanhamento de progresso</span>
    </div>
</div>

<!-- Featured Content -->
<div class="section-header">
    <h3 class="section-title">Conteúdo em Destaque</h3>
    <p class="section-description">Explore os principais conceitos e fundamentos do planejamento de demanda e gestão de fornecedores.</p>
</div>

<div class="grid grid-cols-3">
    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <h4 class="card-title">Fundamentos do Planejamento de Demanda</h4>
        </div>
        <p class="card-description">
            Entenda como equilibrar lead times operacionais com nível de serviço ao cliente através de
            previsões precisas e estrutura hierárquica.
        </p>
        <a href="fundamentos.php" class="card-link">Explorar conteúdo →</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-handshake"></i>
            </div>
            <h4 class="card-title">Gestão de Riscos com Fornecedores</h4>
        </div>
        <p class="card-description">
            Aprenda a mapear riscos em toda a cadeia de suprimentos, monitorar sinais de alerta e
            definir planos de mitigação eficazes.
        </p>
        <a href="gestao-riscos.php" class="card-link">Explorar conteúdo →</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-icon">
                <i class="fa-solid fa-brain"></i>
            </div>
            <h4 class="card-title">O Conceito do Digital Brain</h4>
        </div>
        <p class="card-description">
            Descubra como a plataforma o9 conecta dados, analytics e planejamento em tempo real para
            criar uma "mente digital" da empresa.
        </p>
        <a href="conceito-digital-brain.php" class="card-link">Explorar conteúdo →</a>
    </div>
</div>

<!-- Chapters -->
<div class="section-header mt-8">
    <h3 class="section-title">Módulos de Estudo</h3>
    <p class="section-description">Navegue pelos módulos completos e explore todos os conceitos do planejamento de demanda.</p>
</div>

<div class="grid grid-cols-2">
    <?php foreach ($chapters as $chapter): ?>
        <div class="card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa-solid <?php echo $chapter['icon']; ?>"></i>
                </div>
                <h4 class="card-title"><?php echo htmlspecialchars($chapter['title']); ?></h4>
            </div>
            <p class="card-description">
                <?php echo htmlspecialchars($chapter['description']); ?>
            </p>
            <a href="chapter.php?chapter=<?php echo $chapter['number']; ?>" class="card-link">
                Explorar módulo →
            </a>
        </div>
    <?php endforeach; ?>
</div>

<?php
// Incluir footer
include 'includes/footer.php';
?>