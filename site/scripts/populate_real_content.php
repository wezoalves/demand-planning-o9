<?php

/**
 * Script para recriar o banco de dados com conteúdos reais dos arquivos da pasta book
 */

require_once __DIR__ . '/../config/database.php';

// Função para converter markdown para HTML
function markdownToHtml($markdown)
{
    // Converter cabeçalhos
    $html = preg_replace('/^### (.*$)/m', '<h3>$1</h3>', $markdown);
    $html = preg_replace('/^## (.*$)/m', '<h2>$1</h2>', $html);
    $html = preg_replace('/^# (.*$)/m', '<h1>$1</h1>', $html);

    // Converter listas
    $html = preg_replace('/^\* (.*$)/m', '<li>$1</li>', $html);
    $html = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html);

    // Converter emojis e formatação
    $html = preg_replace('/👉/', '<strong>👉</strong>', $html);
    $html = preg_replace('/✅/', '<strong>✅</strong>', $html);
    $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
    $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);

    // Converter quebras de linha
    $html = str_replace("\n\n", '</p><p>', $html);
    $html = '<p>' . $html . '</p>';

    // Limpar tags vazias
    $html = str_replace('<p></p>', '', $html);

    return $html;
}

// Função para extrair seções de um arquivo markdown
function extractSections($filePath)
{
    $content = file_get_contents($filePath);
    $lines = explode("\n", $content);

    $sections = [];
    $currentSection = null;
    $currentContent = [];

    foreach ($lines as $line) {
        // Verificar se é um cabeçalho de seção (## número. título)
        if (preg_match('/^## (\d+)\. (.+)$/', $line, $matches)) {
            // Salvar seção anterior se existir
            if ($currentSection) {
                $sections[] = [
                    'number' => $currentSection['number'],
                    'title' => $currentSection['title'],
                    'content' => trim(implode("\n", $currentContent))
                ];
            }

            // Iniciar nova seção
            $currentSection = [
                'number' => (int)$matches[1],
                'title' => trim($matches[2])
            ];
            $currentContent = [];
        } else {
            // Adicionar linha ao conteúdo atual
            if ($currentSection) {
                $currentContent[] = $line;
            }
        }
    }

    // Adicionar última seção
    if ($currentSection) {
        $sections[] = [
            'number' => $currentSection['number'],
            'title' => $currentSection['title'],
            'content' => trim(implode("\n", $currentContent))
        ];
    }

    return $sections;
}

// Função para criar slug a partir do título
function createSlug($title)
{
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Configuração dos capítulos
$chapters = [
    [
        'number' => 1,
        'title' => 'Planejamento de Demanda',
        'icon' => 'fa-chart-line',
        'color' => 'blue',
        'description' => 'Fundamentos, estrutura hierárquica, métodos estatísticos, riscos e oportunidades, precisão de forecast e abordagens modernas.',
        'file' => '../../book/01-Planejamento de Demanda.md'
    ],
    [
        'number' => 2,
        'title' => 'Gestão de Relacionamento com Fornecedores',
        'icon' => 'fa-handshake',
        'color' => 'green',
        'description' => 'Gestão de riscos, métricas-chave, colaboração estratégica, visibilidade em múltiplos tiers e IA na antecipação de riscos.',
        'file' => '../../book/02-Gestão de Relacionamento com Fornecedores.md'
    ],
    [
        'number' => 3,
        'title' => 'Integrated Business Planning (IBP)',
        'icon' => 'fa-cogs',
        'color' => 'purple',
        'description' => 'Diferenças entre S&OP e IBP, alinhamento de áreas, ciclo de IBP, colaboração cross-functional e benefícios estratégicos.',
        'file' => '../../book/03-Integrated Business Planning (IBP).md'
    ],
    [
        'number' => 4,
        'title' => 'Revenue Growth Management (RGM)',
        'icon' => 'fa-chart-bar',
        'color' => 'orange',
        'description' => 'Estratégias de precificação, promoções e sortimento, identificação de alavancas de crescimento e simulações para otimizar decisões.',
        'file' => '../../book/04-Revenue Growth Management (RGM).md'
    ],
    [
        'number' => 5,
        'title' => 'Plataforma o9 Digital Brain',
        'icon' => 'fa-brain',
        'color' => 'indigo',
        'description' => 'Conceito do Digital Brain, conexão entre funções, uso de IA, casos de uso em empresas globais e transformação digital.',
        'file' => '../../book/05-Plataforma o9 Digital Brain.md'
    ],
    [
        'number' => 6,
        'title' => 'Transformação Digital',
        'icon' => 'fa-globe',
        'color' => 'teal',
        'description' => 'Mudanças culturais e organizacionais, papel da liderança, exemplos de empresas bem-sucedidas e desafios comuns na transformação.',
        'file' => '../../book/06-Transformação Digital.md'
    ]
];

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();

    echo "Iniciando recriação do banco de dados...\n";

    // Limpar tabelas existentes
    $connection->exec("DELETE FROM user_progress");
    $connection->exec("DELETE FROM contents");
    $connection->exec("DELETE FROM chapters");

    echo "Tabelas limpas.\n";

    // Inserir capítulos
    $chapterStmt = $connection->prepare("
        INSERT INTO chapters (number, title, icon, color, description) 
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($chapters as $chapter) {
        $chapterStmt->execute([
            $chapter['number'],
            $chapter['title'],
            $chapter['icon'],
            $chapter['color'],
            $chapter['description']
        ]);
        echo "Capítulo {$chapter['number']} inserido: {$chapter['title']}\n";
    }

    // Inserir conteúdos
    $contentStmt = $connection->prepare("
        INSERT INTO contents (chapter_id, slug, title, content, order_index) 
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($chapters as $chapter) {
        $filePath = __DIR__ . '/' . $chapter['file'];

        if (!file_exists($filePath)) {
            echo "ERRO: Arquivo não encontrado: {$filePath}\n";
            continue;
        }

        // Obter ID do capítulo
        $chapterStmt = $connection->prepare("SELECT id FROM chapters WHERE number = ?");
        $chapterStmt->execute([$chapter['number']]);
        $chapterId = $chapterStmt->fetchColumn();

        // Extrair seções do arquivo
        $sections = extractSections($filePath);

        foreach ($sections as $section) {
            $slug = createSlug($section['title']);
            $htmlContent = markdownToHtml($section['content']);

            $contentStmt->execute([
                $chapterId,
                $slug,
                $section['title'],
                $htmlContent,
                $section['number']
            ]);

            echo "  - Seção {$section['number']} inserida: {$section['title']}\n";
        }
    }

    echo "\nBanco de dados recriado com sucesso!\n";
    echo "Total de capítulos: " . count($chapters) . "\n";

    // Contar total de conteúdos
    $contentCount = $connection->query("SELECT COUNT(*) FROM contents")->fetchColumn();
    echo "Total de conteúdos: {$contentCount}\n";
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}
