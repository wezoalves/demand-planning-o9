<?php

/**
 * Script para popular o banco de dados com o conteúdo real dos capítulos
 */

require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

// Conteúdo do Capítulo 1 - Fundamentos
$fundamentosContent = '
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
</div>';

// Atualizar conteúdo no banco
$stmt = $db->getConnection()->prepare("
    UPDATE contents 
    SET content = ? 
    WHERE slug = 'fundamentos'
");
$stmt->execute([$fundamentosContent]);

echo "Conteúdo atualizado com sucesso!\n";
