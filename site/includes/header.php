<?php
require_once __DIR__ . '/functions.php';

// Obter informações da página atual
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$pageTitle = 'o9 Learning Hub';

// Definir título específico da página
if (isset($customTitle)) {
    $pageTitle = $customTitle . ' - ' . $pageTitle;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <div class="sidebar-header">
                    <div class="sidebar-logo">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>o9 Learning Hub</span>
                    </div>
                    <button class="sidebar-trigger" id="sidebarToggle">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                </div>

                <!-- Search -->
                <div class="search-container">
                    <i class="fa-solid fa-search search-icon"></i>
                    <input type="text" id="searchInput" placeholder="Buscar conceitos, capítulos…" class="search-input">
                </div>

                <!-- Navigation -->
                <div class="nav-section">
                    <?php echo generateNavigation(); ?>
                </div>
            </div>

            <!-- Progress Footer -->
            <div class="sidebar-footer">
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-label">Progresso Geral</span>
                        <span id="overallProgress" class="progress-text">
                            <?php
                            $progress = getOverallProgress();
                            $readCount = $db->getProgress($_SESSION['session_id']);
                            echo $readCount . '/35 conteúdos lidos';
                            ?>
                        </span>
                    </div>
                    <div class="progress-bar-container">
                        <div id="progressBar" class="progress-bar"
                            style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Header -->
            <header class="main-header">
                <div>
                    <?php if (isset($breadcrumb)): ?>
                        <nav class="breadcrumb">
                            <?php echo $breadcrumb; ?>
                        </nav>
                    <?php endif; ?>
                    <h2 class="page-title"><?php echo isset($pageHeading) ? $pageHeading : 'o9 Learning Hub'; ?></h2>
                    <?php if (isset($pageDescription)): ?>
                        <p class="page-description"><?php echo $pageDescription; ?></p>
                    <?php endif; ?>
                </div>
            </header>

            <!-- Content -->
            <div class="content-area">