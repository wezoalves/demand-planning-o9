// Função para marcar conteúdo como lido
function markAsRead(chapterId) {
    const readChapters = JSON.parse(localStorage.getItem('readChapters') || '[]');

    if (!readChapters.includes(chapterId)) {
        readChapters.push(chapterId);
        localStorage.setItem('readChapters', JSON.stringify(readChapters));
    }

    // Atualizar interface
    const button = document.getElementById('markAsRead');
    const status = document.getElementById('readStatus');

    if (button) {
        button.style.display = 'none';
    }
    if (status) {
        status.classList.remove('hidden');
    }

    // Atualizar progresso na sidebar
    updateProgressInSidebar();
}

// Função para verificar se já foi lido
function checkIfRead(chapterId) {
    const readChapters = JSON.parse(localStorage.getItem('readChapters') || '[]');
    return readChapters.includes(chapterId);
}

// Função para atualizar progresso na sidebar
function updateProgressInSidebar() {
    const readChapters = JSON.parse(localStorage.getItem('readChapters') || '[]');
    const totalChapters = 6;
    const progress = (readChapters.length / totalChapters) * 100;

    // Atualizar indicadores visuais na sidebar
    const chapterLinks = document.querySelectorAll('nav a[href*="capitulo"]');
    chapterLinks.forEach(link => {
        const chapterId = link.getAttribute('href').replace('.html', '');
        if (readChapters.includes(chapterId)) {
            // Adicionar ícone de check
            const icon = link.querySelector('i.fa-check-circle');
            if (!icon) {
                const checkIcon = document.createElement('i');
                checkIcon.className = 'fa-solid fa-check-circle text-green-500 ml-auto';
                link.appendChild(checkIcon);
            }
        }
    });

    // Atualizar progresso geral se estiver na página inicial
    const progressElement = document.getElementById('overallProgress');
    if (progressElement) {
        progressElement.textContent = `${readChapters.length}/${totalChapters} capítulos lidos`;
    }
}

// Função para limpar progresso (para testes)
function clearProgress() {
    localStorage.removeItem('readChapters');
    location.reload();
}

// Função para exportar progresso
function exportProgress() {
    const readChapters = JSON.parse(localStorage.getItem('readChapters') || '[]');
    const data = {
        readChapters: readChapters,
        totalChapters: 6,
        progress: (readChapters.length / 6) * 100,
        exportDate: new Date().toISOString()
    };

    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'o9-learning-progress.json';
    a.click();
    URL.revokeObjectURL(url);
}

// Função para importar progresso
function importProgress() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.json';
    input.onchange = function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                try {
                    const data = JSON.parse(e.target.result);
                    if (data.readChapters) {
                        localStorage.setItem('readChapters', JSON.stringify(data.readChapters));
                        location.reload();
                    }
                } catch (error) {
                    alert('Erro ao importar arquivo de progresso');
                }
            };
            reader.readAsText(file);
        }
    };
    input.click();
}

// Função para mostrar estatísticas de progresso
function showProgressStats() {
    const readChapters = JSON.parse(localStorage.getItem('readChapters') || '[]');
    const totalChapters = 6;
    const progress = (readChapters.length / totalChapters) * 100;

    const stats = `
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
            <h3 class="text-lg font-semibold mb-4">Seu Progresso</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span>Capítulos lidos:</span>
                    <span class="font-semibold">${readChapters.length}/${totalChapters}</span>
                </div>
                <div class="flex justify-between">
                    <span>Progresso:</span>
                    <span class="font-semibold">${progress.toFixed(1)}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: ${progress}%"></div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <button onclick="clearProgress()" class="text-red-600 hover:text-red-800 text-sm">Limpar Progresso</button>
                <button onclick="exportProgress()" class="text-blue-600 hover:text-blue-800 text-sm ml-4">Exportar</button>
                <button onclick="importProgress()" class="text-blue-600 hover:text-blue-800 text-sm ml-4">Importar</button>
            </div>
        </div>
    `;

    // Criar modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="relative">
            ${stats}
            <button onclick="this.parentElement.parentElement.remove()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(modal);
}

// Inicializar quando a página carregar
document.addEventListener('DOMContentLoaded', function () {
    // Adicionar botão de estatísticas na página inicial
    if (window.location.pathname.includes('index.html') || window.location.pathname.endsWith('/')) {
        const header = document.querySelector('header');
        if (header) {
            const statsButton = document.createElement('button');
            statsButton.innerHTML = '<i class="fa-solid fa-chart-bar mr-2"></i>Progresso';
            statsButton.className = 'bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm';
            statsButton.onclick = showProgressStats;
            header.appendChild(statsButton);
        }
    }

    // Atualizar progresso na sidebar
    updateProgressInSidebar();

    // Adicionar listeners para botões de marcação
    const markAsReadButton = document.getElementById('markAsRead');
    if (markAsReadButton) {
        const chapterId = window.location.pathname.split('/').pop().replace('.html', '');
        markAsReadButton.onclick = () => markAsRead(chapterId);

        // Verificar se já foi lido
        if (checkIfRead(chapterId)) {
            markAsReadButton.style.display = 'none';
            const status = document.getElementById('readStatus');
            if (status) {
                status.classList.remove('hidden');
            }
        }
    }
});

// Adicionar funcionalidade de busca simples
function searchContent(query) {
    const searchInput = document.querySelector('input[placeholder*="Buscar"]');
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase();
            const chapterCards = document.querySelectorAll('.bg-blue-50, .bg-green-50, .bg-purple-50, .bg-orange-50, .bg-indigo-50, .bg-teal-50');

            chapterCards.forEach(card => {
                const title = card.querySelector('h4').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();

                if (title.includes(query) || description.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
}

// Inicializar busca
document.addEventListener('DOMContentLoaded', searchContent);
