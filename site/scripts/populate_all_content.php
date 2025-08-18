<?php

/**
 * Script para popular o banco de dados com todo o conteúdo real dos capítulos
 */

require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

// Array com todo o conteúdo real
$allContent = [
    // Capítulo 1 - Planejamento de Demanda
    'fundamentos' => '
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Fundamentos do Planejamento de Demanda</h3>
        
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <p class="text-blue-800 font-medium">O planejamento de demanda existe para equilibrar dois fatores críticos da cadeia de suprimentos:</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-clock text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Lead times da operação</h4>
                        <p class="text-gray-700 text-sm">O tempo necessário para comprar insumos, produzir e entregar produtos (buy–make–sell).</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-users text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Nível de serviço ao cliente</h4>
                        <p class="text-gray-700 text-sm">A capacidade de atender pedidos dentro do prazo que o cliente considera aceitável.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="text-gray-700 mb-6 leading-relaxed">
            Se a empresa esperar o pedido chegar para começar a comprar e produzir, dificilmente conseguirá atender no tempo esperado. 
            Por isso, é preciso <strong>antecipar a demanda</strong>, garantindo que materiais estejam comprados, fábricas preparadas 
            e estoques dimensionados.
        </p>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-800">
                <strong>Em resumo:</strong> O planejamento de demanda serve para alinhar <strong>estoques, produção e distribuição</strong> 
                às expectativas do mercado, evitando rupturas e excesso de produtos parados.
            </p>
        </div>
    </div>',

    'estrutura-hierarquia' => '
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Estrutura e Hierarquia do Plano de Demanda</h3>
        
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <p class="text-blue-800 font-medium">A estrutura hierárquica organiza as previsões de demanda em diferentes níveis de agregação.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-2">Nível Corporativo</h4>
                <p class="text-sm text-gray-600">Visão macro da demanda total da empresa, útil para planejamento estratégico.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-2">Nível Regional</h4>
                <p class="text-sm text-gray-600">Demanda por região geográfica, importante para distribuição e logística.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-2">Nível Produto</h4>
                <p class="text-sm text-gray-600">Demanda específica por produto, essencial para produção e estoque.</p>
            </div>
        </div>
        
        <p class="text-gray-700 mb-6 leading-relaxed">
            A hierarquia permite <strong>agregar e desagregar</strong> previsões conforme necessário, 
            garantindo consistência entre os diferentes níveis de planejamento.
        </p>
    </div>',

    'metodos-estatisticos' => '
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Métodos Estatísticos, Agregação e Desagregação</h3>
        
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <p class="text-blue-800 font-medium">Diferentes métodos estatísticos são aplicados conforme o padrão de demanda.</p>
        </div>
        
        <div class="space-y-4 mb-6">
            <div class="flex items-start">
                <i class="fa-solid fa-chart-line text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Média Móvel</h4>
                    <p class="text-gray-700 text-sm">Ideal para demandas estáveis, calcula a média dos últimos períodos.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <i class="fa-solid fa-chart-line text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Suavização Exponencial</h4>
                    <p class="text-gray-700 text-sm">Dá mais peso aos dados recentes, adequado para tendências.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <i class="fa-solid fa-chart-line text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Regressão Linear</h4>
                    <p class="text-gray-700 text-sm">Identifica tendências lineares e sazonalidade.</p>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-yellow-800">
                <strong>Importante:</strong> A escolha do método depende do padrão histórico da demanda, 
                sazonalidade e disponibilidade de dados.
            </p>
        </div>
    </div>',

    // Capítulo 2 - Gestão de Relacionamento com Fornecedores
    'gestao-riscos' => '
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Gestão de Riscos com Fornecedores</h3>
        
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <p class="text-red-800 font-medium">As cadeias de suprimentos atuais são complexas, globais e vulneráveis.</p>
        </div>
        
        <p class="text-gray-700 mb-6 leading-relaxed">
            Eventos externos — como desastres naturais, crises sanitárias ou instabilidade geopolítica — podem interromper 
            o fornecimento de insumos críticos. Por isso, a <strong>gestão de risco com fornecedores</strong> precisa ir 
            além do simples acompanhamento de entregas.
        </p>
        
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-map text-green-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Mapear Riscos</h4>
                        <p class="text-gray-700 text-sm">Riscos potenciais em toda a cadeia (do fornecedor direto até subfornecedores).</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-eye text-green-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Monitorar Sinais</h4>
                        <p class="text-gray-700 text-sm">Capacidade de produção, saúde financeira, riscos ambientais e políticos.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-shield-alt text-green-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Planos de Mitigação</h4>
                        <p class="text-gray-700 text-sm">Estoques de segurança, múltiplas fontes de fornecimento e contratos de contingência.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>',

    'metricas-chave' => '
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Métricas-Chave: Tempo de Reação e Tempo de Resolução</h3>
        
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <p class="text-green-800 font-medium">Duas métricas fundamentais para avaliar a capacidade de resposta da cadeia de suprimentos.</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-2 text-green-600">Tempo de Reação</h4>
                <p class="text-gray-700 text-sm mb-3">Tempo entre a identificação de um problema e o início da ação corretiva.</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Monitoramento em tempo real</li>
                    <li>• Alertas automáticos</li>
                    <li>• Processos de escalação</li>
                </ul>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-2 text-green-600">Tempo de Resolução</h4>
                <p class="text-gray-700 text-sm mb-3">Tempo total para resolver completamente o problema identificado.</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Planos de contingência</li>
                    <li>• Recursos alternativos</li>
                    <li>• Coordenação entre áreas</li>
                </ul>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-green-800">
                <strong>Objetivo:</strong> Minimizar ambos os tempos para garantir continuidade operacional 
                e satisfação do cliente.
            </p>
        </div>
    </div>',

    // Capítulo 5 - Digital Brain
    'conceito-digital-brain' => '
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Conceito de Digital Brain</h3>
        
        <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 mb-6">
            <p class="text-indigo-800 font-medium">O <strong>Digital Brain</strong> é a plataforma integrada da o9 que conecta dados, analytics e planejamento em tempo real.</p>
        </div>
        
        <p class="text-gray-700 mb-6 leading-relaxed">
            A ideia central é criar uma "mente digital" da empresa, capaz de processar informações de todas as áreas 
            e gerar insights preditivos para tomada de decisão estratégica.
        </p>
        
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-database text-indigo-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Integração de Dados</h4>
                        <p class="text-gray-700 text-sm">Conecta dados de vendas, supply, finanças e procurement em uma única plataforma.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-brain text-indigo-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">IA e Analytics</h4>
                        <p class="text-gray-700 text-sm">Aplica machine learning para gerar insights preditivos e otimizar decisões.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-sync text-indigo-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Tempo Real</h4>
                        <p class="text-gray-700 text-sm">Processamento e análise em tempo real para agilidade na tomada de decisão.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
            <p class="text-indigo-800">
                Em vez de cada área operar em seu próprio sistema e com números diferentes, o Digital Brain cria 
                <strong>uma única versão da verdade</strong>.
            </p>
        </div>
    </div>',

    'conexao-funcoes' => '
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Conexão entre Funções da Empresa</h3>
        
        <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 mb-6">
            <p class="text-indigo-800 font-medium">O Digital Brain elimina os silos organizacionais conectando todas as funções da empresa.</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-3 text-indigo-600">Funções Integradas</h4>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• <strong>Vendas:</strong> Previsões e pedidos em tempo real</li>
                    <li>• <strong>Produção:</strong> Capacidade e planejamento</li>
                    <li>• <strong>Logística:</strong> Distribuição e estoques</li>
                    <li>• <strong>Finanças:</strong> Custos e orçamentos</li>
                    <li>• <strong>Procurement:</strong> Fornecedores e compras</li>
                </ul>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-3 text-indigo-600">Benefícios da Integração</h4>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Visão única dos dados</li>
                    <li>• Decisões mais rápidas</li>
                    <li>• Melhor coordenação</li>
                    <li>• Redução de conflitos</li>
                    <li>• Otimização global</li>
                </ul>
            </div>
        </div>
        
        <p class="text-gray-700 mb-6 leading-relaxed">
            A integração permite que mudanças em uma área sejam automaticamente refletidas em todas as outras, 
            criando um <strong>ecossistema de planejamento colaborativo</strong>.
        </p>
    </div>'
];

// Atualizar conteúdos no banco
$stmt = $db->getConnection()->prepare("
    UPDATE contents 
    SET content = ? 
    WHERE slug = ?
");

foreach ($allContent as $slug => $content) {
    $stmt->execute([$content, $slug]);
    echo "Conteúdo atualizado para: " . $slug . "\n";
}

echo "Todo o conteúdo real foi atualizado com sucesso!\n";
