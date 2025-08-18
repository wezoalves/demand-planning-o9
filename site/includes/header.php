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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-o8CgHn7SXsAD69vLcBGo3S9m3edPxDdL5bsZ5qmyVriJvhf7t4JUcs72/0X5A5c0hTojcfEwEgbhZL+Y8qP3Tg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }

        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <nav class="w-80 bg-white shadow-lg overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <i class="fa-solid fa-graduation-cap text-blue-600 text-2xl mr-3"></i>
                    <h1 class="text-xl font-bold text-gray-800">o9 Learning Hub</h1>
                </div>

                <!-- Search -->
                <div class="mb-6">
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Buscar conceitos, capítulos…"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Progress -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-blue-800">Progresso Geral</span>
                        <span id="overallProgress" class="text-sm text-blue-600">
                            <?php
                            $progress = getOverallProgress();
                            $readCount = $db->getProgress($_SESSION['session_id']);
                            echo $readCount . '/35 conteúdos lidos';
                            ?>
                        </span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-2">
                        <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                            style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="space-y-2">
                    <?php echo generateNavigation(); ?>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <?php if (isset($breadcrumb)): ?>
                                <nav class="text-sm text-gray-500 mb-2">
                                    <?php echo $breadcrumb; ?>
                                </nav>
                            <?php endif; ?>
                            <h2 class="text-3xl font-bold"><?php echo isset($pageHeading) ? $pageHeading : 'o9 Learning Hub'; ?></h2>
                            <?php if (isset($pageDescription)): ?>
                                <p class="text-gray-600 mt-2"><?php echo $pageDescription; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6">