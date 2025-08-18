<?php
require_once __DIR__ . '/includes/functions.php';

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = Database::getInstance();
    $connection = $db->getConnection();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_content':
                try {
                    $stmt = $connection->prepare("
                        UPDATE contents 
                        SET title = ?, content = ?, order_index = ? 
                        WHERE id = ?
                    ");
                    $result = $stmt->execute([
                        $_POST['title'],
                        $_POST['content'],
                        $_POST['order_index'],
                        $_POST['content_id']
                    ]);

                    if ($result) {
                        $message = "Conteúdo atualizado com sucesso!";
                    } else {
                        $message = "Erro ao atualizar conteúdo!";
                    }
                } catch (Exception $e) {
                    $message = "Erro: " . $e->getMessage();
                }
                break;

            case 'create_content':
                $stmt = $connection->prepare("
                    INSERT INTO contents (chapter_id, slug, title, content, order_index) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $slug = createSlug($_POST['title']);
                $stmt->execute([
                    $_POST['chapter_id'],
                    $slug,
                    $_POST['title'],
                    $_POST['content'],
                    $_POST['order_index']
                ]);
                $message = "Conteúdo criado com sucesso!";
                break;

            case 'update_chapter':
                try {
                    $stmt = $connection->prepare("
                        UPDATE chapters 
                        SET title = ?, description = ? 
                        WHERE id = ?
                    ");
                    $result = $stmt->execute([
                        $_POST['title'],
                        $_POST['description'],
                        $_POST['chapter_id']
                    ]);

                    if ($result) {
                        $message = "Capítulo atualizado com sucesso!";
                    } else {
                        $message = "Erro ao atualizar capítulo!";
                    }
                } catch (Exception $e) {
                    $message = "Erro: " . $e->getMessage();
                }
                break;

            case 'create_chapter':
                $stmt = $connection->prepare("
                    INSERT INTO chapters (number, title, icon, color, description) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_POST['number'],
                    $_POST['title'],
                    $_POST['icon'],
                    $_POST['color'],
                    $_POST['description']
                ]);
                $message = "Capítulo criado com sucesso!";
                break;

            case 'delete_content':
                try {
                    $stmt = $connection->prepare("
                        DELETE FROM contents 
                        WHERE id = ?
                    ");
                    $result = $stmt->execute([$_POST['content_id']]);

                    if ($result) {
                        $message = "Conteúdo excluído com sucesso!";
                    } else {
                        $message = "Erro ao excluir conteúdo!";
                    }
                } catch (Exception $e) {
                    $message = "Erro: " . $e->getMessage();
                }
                break;
        }
    }
}

// Função para criar slug
function createSlug($title)
{
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Obter dados
$db = Database::getInstance();
$chapters = $db->getAllChapters();
$contents = [];

if (isset($_GET['chapter_id'])) {
    $contents = $db->getChapterContents($_GET['chapter_id']);
}

$selectedContent = null;
if (isset($_GET['content_id'])) {
    $selectedContent = $db->getContent($_GET['content_id']);
}

// Configurações de ícones e cores
$icons = [
    'fa-chart-line',
    'fa-handshake',
    'fa-cogs',
    'fa-chart-bar',
    'fa-brain',
    'fa-globe',
    'fa-book',
    'fa-graduation-cap',
    'fa-lightbulb',
    'fa-rocket',
    'fa-users',
    'fa-cog'
];

$colors = [
    'blue',
    'green',
    'purple',
    'orange',
    'indigo',
    'teal',
    'red',
    'yellow',
    'pink',
    'gray'
];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - o9 Learning Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
        }

        /* Estilos para o editor Quill */
        .ql-editor {
            min-height: 200px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        .ql-toolbar {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            border-color: #d1d5db;
        }

        .ql-container {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            border-color: #d1d5db;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fa-solid fa-cog mr-3 text-blue-600"></i>
                        Administração
                    </h1>
                    <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="fa-solid fa-home mr-2"></i>
                        Voltar ao Site
                    </a>
                </div>
            </div>
        </header>

        <!-- Message -->
        <?php if (isset($message)): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Capítulos</h2>
                            <button onclick="openChapterModal()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                <i class="fa-solid fa-plus mr-1"></i> Novo
                            </button>
                        </div>

                        <div class="space-y-2">
                            <?php foreach ($chapters as $chapter): ?>
                                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center">
                                        <i class="fa-solid <?php echo $chapter['icon']; ?> text-<?php echo $chapter['color']; ?>-600 mr-3"></i>
                                        <div>
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($chapter['title']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($chapter['description']); ?></div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="editChapter(<?php echo htmlspecialchars(json_encode($chapter)); ?>)"
                                            class="text-blue-600 hover:text-blue-800">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                        <a href="?chapter_id=<?php echo $chapter['id']; ?>"
                                            class="text-green-600 hover:text-green-800">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow">

                        <!-- Content Selection -->
                        <div class="p-6 border-b">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold text-gray-900">Gerenciar Conteúdos</h2>
                                <button onclick="openContentModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                    <i class="fa-solid fa-plus mr-2"></i> Novo Conteúdo
                                </button>
                            </div>

                            <?php if (!empty($contents)): ?>
                                <form method="GET" class="mb-4">
                                    <input type="hidden" name="chapter_id" value="<?php echo $_GET['chapter_id']; ?>">
                                    <select name="content_id" onchange="this.form.submit()" class="w-full p-3 border border-gray-300 rounded-lg">
                                        <option value="">Selecione um conteúdo para editar...</option>
                                        <?php foreach ($contents as $content): ?>
                                            <option value="<?php echo $content['id']; ?>"
                                                <?php echo (isset($_GET['content_id']) && $_GET['content_id'] == $content['id']) ? 'selected' : ''; ?>>
                                                <?php echo $content['order_index']; ?>. <?php echo htmlspecialchars($content['title']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            <?php else: ?>
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fa-solid fa-file-lines text-4xl mb-4"></i>
                                    <p>Selecione um capítulo para ver seus conteúdos</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Content Editor -->
                        <?php if ($selectedContent): ?>
                            <div class="p-6">
                                <form method="POST" onsubmit="return captureContent()">
                                    <input type="hidden" name="action" value="update_content">
                                    <input type="hidden" name="content_id" value="<?php echo $selectedContent['id']; ?>">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                                            <input type="text" name="title" value="<?php echo htmlspecialchars($selectedContent['title']); ?>"
                                                class="w-full p-3 border border-gray-300 rounded-lg" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Ordem</label>
                                            <input type="number" name="order_index" value="<?php echo $selectedContent['order_index']; ?>"
                                                class="w-full p-3 border border-gray-300 rounded-lg" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Conteúdo</label>
                                        <div id="content-editor"><?php echo $selectedContent['content']; ?></div>
                                        <input type="hidden" name="content" id="content-input">
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <button type="button" onclick="confirmDelete(<?php echo $selectedContent['id']; ?>, '<?php echo htmlspecialchars($selectedContent['title']); ?>')"
                                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                                            <i class="fa-solid fa-trash mr-2"></i> Excluir
                                        </button>

                                        <div class="flex space-x-3">
                                            <a href="admin.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                                                <i class="fa-solid fa-save mr-2"></i> Salvar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chapter Modal -->
    <div id="chapterModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 id="chapterModalTitle" class="text-xl font-semibold">Novo Capítulo</h3>
                <button onclick="closeChapterModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" id="chapterForm">
                <input type="hidden" name="action" id="chapterAction" value="create_chapter">
                <input type="hidden" name="chapter_id" id="chapterId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                        <input type="number" name="number" id="chapterNumber" required
                            class="w-full p-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ícone</label>
                        <select name="icon" id="chapterIcon" class="w-full p-3 border border-gray-300 rounded-lg">
                            <?php foreach ($icons as $icon): ?>
                                <option value="<?php echo $icon; ?>"><?php echo $icon; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                    <input type="text" name="title" id="chapterTitle" required
                        class="w-full p-3 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cor</label>
                    <select name="color" id="chapterColor" class="w-full p-3 border border-gray-300 rounded-lg">
                        <?php foreach ($colors as $color): ?>
                            <option value="<?php echo $color; ?>"><?php echo ucfirst($color); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                    <textarea name="description" id="chapterDescription" rows="3"
                        class="w-full p-3 border border-gray-300 rounded-lg"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeChapterModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Content Modal -->
    <div id="contentModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Novo Conteúdo</h3>
                <button onclick="closeContentModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" id="contentForm">
                <input type="hidden" name="action" value="create_content">
                <input type="hidden" name="chapter_id" value="<?php echo $_GET['chapter_id'] ?? ''; ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                        <input type="text" name="title" required
                            class="w-full p-3 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ordem</label>
                        <input type="number" name="order_index" required
                            class="w-full p-3 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Conteúdo</label>
                    <div id="new-content-editor"></div>
                    <input type="hidden" name="content" id="new-content-input">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeContentModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Criar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-red-600">Confirmar Exclusão</h3>
                <button onclick="closeDeleteModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                    <p class="text-gray-700">Tem certeza que deseja excluir o conteúdo:</p>
                </div>
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h4 id="deleteContentTitle" class="font-semibold text-gray-900"></h4>
                </div>
                <p class="text-sm text-gray-600 mt-3">
                    <strong>Atenção:</strong> Esta ação não pode ser desfeita. O conteúdo será permanentemente removido.
                </p>
            </div>

            <form method="POST" id="deleteForm">
                <input type="hidden" name="action" value="delete_content">
                <input type="hidden" name="content_id" id="deleteContentId">

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                        <i class="fa-solid fa-trash mr-2"></i> Excluir Definitivamente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Variáveis globais para os editores Quill
        let contentEditor = null;
        let newContentEditor = null;

        // Inicializar Quill.js
        function initQuill() {
            // Editor para conteúdo existente
            if (document.getElementById('content-editor')) {
                contentEditor = new Quill('#content-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{
                                'header': [1, 2, 3, false]
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            [{
                                'color': []
                            }, {
                                'background': []
                            }],
                            [{
                                'align': []
                            }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Digite o conteúdo aqui...'
                });

                // Aguardar um pouco para garantir que o editor foi inicializado
                setTimeout(() => {
                    console.log('Editor de conteúdo inicializado');
                }, 100);
            }

            // Editor para novo conteúdo
            if (document.getElementById('new-content-editor')) {
                newContentEditor = new Quill('#new-content-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{
                                'header': [1, 2, 3, false]
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            [{
                                'color': []
                            }, {
                                'background': []
                            }],
                            [{
                                'align': []
                            }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Digite o conteúdo aqui...'
                });

                // Aguardar um pouco para garantir que o editor foi inicializado
                setTimeout(() => {
                    console.log('Editor de novo conteúdo inicializado');
                }, 100);
            }
        }

        // Função para obter conteúdo HTML dos editores
        function getEditorContent(editor) {
            if (editor) {
                return editor.root.innerHTML;
            }
            return '';
        }

        // Função para definir conteúdo HTML nos editores
        function setEditorContent(editor, content) {
            if (editor) {
                editor.root.innerHTML = content;
            }
        }

        // Inicializar editores quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            initQuill();

            // Configurar envio de formulários para incluir conteúdo dos editores
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Para formulário de edição de conteúdo
                    if (contentEditor && document.getElementById('content-input')) {
                        const content = getEditorContent(contentEditor);
                        document.getElementById('content-input').value = content;
                        console.log('Conteúdo capturado:', content);
                    }

                    // Para formulário de novo conteúdo
                    if (newContentEditor && document.getElementById('new-content-input')) {
                        const content = getEditorContent(newContentEditor);
                        document.getElementById('new-content-input').value = content;
                        console.log('Novo conteúdo capturado:', content);
                    }
                });
            });
        });

        // Função adicional para capturar conteúdo antes do envio
        function captureContent() {
            console.log('Função captureContent chamada');

            if (contentEditor && document.getElementById('content-input')) {
                try {
                    const content = getEditorContent(contentEditor);
                    document.getElementById('content-input').value = content;
                    console.log('Conteúdo capturado na função:', content);
                    console.log('Valor do input após captura:', document.getElementById('content-input').value);
                } catch (error) {
                    console.error('Erro ao capturar conteúdo:', error);
                }
            } else {
                console.log('Editor ou input não encontrado');
                console.log('contentEditor:', contentEditor);
                console.log('content-input:', document.getElementById('content-input'));
            }

            return true; // Sempre retorna true para permitir o envio
        }

        // Modal functions
        function openChapterModal() {
            document.getElementById('chapterModal').style.display = 'block';
            document.getElementById('chapterModalTitle').textContent = 'Novo Capítulo';
            document.getElementById('chapterAction').value = 'create_chapter';
            document.getElementById('chapterForm').reset();
        }

        function closeChapterModal() {
            document.getElementById('chapterModal').style.display = 'none';
        }

        function editChapter(chapter) {
            document.getElementById('chapterModalTitle').textContent = 'Editar Capítulo';
            document.getElementById('chapterAction').value = 'update_chapter';
            document.getElementById('chapterId').value = chapter.id;
            document.getElementById('chapterNumber').value = chapter.number;
            document.getElementById('chapterTitle').value = chapter.title;
            document.getElementById('chapterIcon').value = chapter.icon;
            document.getElementById('chapterColor').value = chapter.color;
            document.getElementById('chapterDescription').value = chapter.description;
            document.getElementById('chapterModal').style.display = 'block';
        }

        function openContentModal() {
            document.getElementById('contentModal').style.display = 'block';
            document.getElementById('contentForm').reset();
            if (newContentEditor) {
                setEditorContent(newContentEditor, '');
            }
        }

        function closeContentModal() {
            document.getElementById('contentModal').style.display = 'none';
        }

        // Funções para o modal de exclusão
        function confirmDelete(contentId, contentTitle) {
            document.getElementById('deleteContentId').value = contentId;
            document.getElementById('deleteContentTitle').textContent = contentTitle;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>

</html>