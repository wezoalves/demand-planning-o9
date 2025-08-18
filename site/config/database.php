<?php

/**
 * Configuração do banco de dados SQLite
 */

class Database
{
    private $db;
    private static $instance = null;

    private function __construct()
    {
        $dbPath = __DIR__ . '/../data/o9_learning.db';
        $dbDir = dirname($dbPath);

        // Criar diretório se não existir
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }

        try {
            $this->db = new PDO('sqlite:' . $dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            die('Erro de conexão com banco de dados: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->db;
    }

    private function createTables()
    {
        // Tabela de capítulos
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS chapters (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                number INTEGER UNIQUE NOT NULL,
                title TEXT NOT NULL,
                icon TEXT NOT NULL,
                color TEXT NOT NULL,
                description TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Tabela de conteúdos (assuntos)
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS contents (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                chapter_id INTEGER NOT NULL,
                slug TEXT UNIQUE NOT NULL,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                order_index INTEGER NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (chapter_id) REFERENCES chapters (id)
            )
        ");

        // Tabela de progresso do usuário
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS user_progress (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id TEXT NOT NULL,
                content_id INTEGER NOT NULL,
                is_read BOOLEAN DEFAULT 0,
                read_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (content_id) REFERENCES contents (id),
                UNIQUE(session_id, content_id)
            )
        ");

        // Inserir dados iniciais se as tabelas estiverem vazias
        $this->insertInitialData();
    }

    private function insertInitialData()
    {
        // Verificar se já existem capítulos
        $stmt = $this->db->query("SELECT COUNT(*) FROM chapters");
        if ($stmt->fetchColumn() == 0) {
            $this->insertChapters();
            $this->insertContents();
        }
    }

    private function insertChapters()
    {
        $chapters = [
            [1, 'Planejamento de Demanda', 'fa-chart-line', 'blue', 'Fundamentos, estrutura hierárquica, métodos estatísticos, riscos e oportunidades, precisão de forecast e abordagens modernas.'],
            [2, 'Gestão de Relacionamento com Fornecedores', 'fa-handshake', 'green', 'Gestão de riscos, métricas-chave, colaboração estratégica, visibilidade em múltiplos tiers e IA na antecipação de riscos.'],
            [3, 'Integrated Business Planning (IBP)', 'fa-cogs', 'purple', 'Diferenças entre S&OP e IBP, alinhamento de áreas, ciclo de IBP, colaboração cross-functional e benefícios estratégicos.'],
            [4, 'Revenue Growth Management (RGM)', 'fa-chart-bar', 'orange', 'Estratégias de precificação, promoções e sortimento, identificação de alavancas de crescimento e simulações para otimizar decisões.'],
            [5, 'Plataforma o9 Digital Brain', 'fa-brain', 'indigo', 'Conceito do Digital Brain, conexão entre funções, uso de IA, casos de uso em empresas globais e transformação digital.'],
            [6, 'Transformação Digital', 'fa-globe', 'teal', 'Mudanças culturais e organizacionais, papel da liderança, exemplos de empresas bem-sucedidas e desafios comuns na transformação.']
        ];

        $stmt = $this->db->prepare("
            INSERT INTO chapters (number, title, icon, color, description) 
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($chapters as $chapter) {
            $stmt->execute($chapter);
        }
    }

    private function insertContents()
    {
        $contents = [
            // Capítulo 1
            [1, 'fundamentos', 'Fundamentos do Planejamento de Demanda', 1, '<p>Conteúdo sobre fundamentos do planejamento de demanda...</p>'],
            [1, 'estrutura-hierarquia', 'Estrutura e Hierarquia do Plano de Demanda', 2, '<p>Conteúdo sobre estrutura e hierarquia...</p>'],
            [1, 'metodos-estatisticos', 'Métodos Estatísticos, Agregação e Desagregação', 3, '<p>Conteúdo sobre métodos estatísticos...</p>'],
            [1, 'riscos-oportunidades', 'Riscos e Oportunidades', 4, '<p>Conteúdo sobre riscos e oportunidades...</p>'],
            [1, 'precisao-forecast', 'Precisão de Forecast e o Cockpit de Análise', 5, '<p>Conteúdo sobre precisão de forecast...</p>'],
            [1, 'dificuldades-varejo', 'Dificuldades Práticas no Varejo', 6, '<p>Conteúdo sobre dificuldades no varejo...</p>'],
            [1, 'abordagens-modernas', 'Abordagens Modernas: Ágil, Digital Twins e IA', 7, '<p>Conteúdo sobre abordagens modernas...</p>'],

            // Capítulo 2
            [2, 'gestao-riscos', 'Gestão de Riscos com Fornecedores', 1, '<p>Conteúdo sobre gestão de riscos...</p>'],
            [2, 'metricas-chave', 'Métricas-Chave: Tempo de Reação e Tempo de Resolução', 2, '<p>Conteúdo sobre métricas-chave...</p>'],
            [2, 'colaboracao-fornecedores', 'Colaboração com Fornecedores: Muito Além do Básico', 3, '<p>Conteúdo sobre colaboração...</p>'],
            [2, 'visibilidade-multiplos-niveis', 'Visibilidade em Múltiplos Níveis (Tiers)', 4, '<p>Conteúdo sobre visibilidade...</p>'],
            [2, 'erros-percepcao-risco', 'Principais Erros de Percepção sobre Risco', 5, '<p>Conteúdo sobre erros de percepção...</p>'],
            [2, 'casos-praticos', 'Casos Práticos', 6, '<p>Conteúdo sobre casos práticos...</p>'],
            [2, 'ia-genai-riscos', 'IA e GenAI na Antecipação de Riscos', 7, '<p>Conteúdo sobre IA e GenAI...</p>'],
            [2, 'segmentacao-onboarding', 'Segmentação e Onboarding de Fornecedores', 8, '<p>Conteúdo sobre segmentação...</p>'],

            // Capítulo 3
            [3, 'diferenca-sop-ibp', 'Diferença entre S&OP e IBP', 1, '<p>Conteúdo sobre diferença entre S&OP e IBP...</p>'],
            [3, 'alinhamento-areas', 'Alinhamento das Áreas da Empresa', 2, '<p>Conteúdo sobre alinhamento de áreas...</p>'],
            [3, 'ciclo-ibp', 'Ciclo de IBP', 3, '<p>Conteúdo sobre ciclo de IBP...</p>'],
            [3, 'colaboracao-cross-functional', 'Colaboração Cross-Functional e Simulações', 4, '<p>Conteúdo sobre colaboração cross-functional...</p>'],
            [3, 'beneficios-ibp', 'Benefícios do IBP', 5, '<p>Conteúdo sobre benefícios do IBP...</p>'],

            // Capítulo 4
            [4, 'estrategias-precificacao', 'Estratégias de Precificação, Promoções e Sortimento', 1, '<p>Conteúdo sobre estratégias de precificação...</p>'],
            [4, 'alavancas-crescimento', 'Identificação de Alavancas de Crescimento', 2, '<p>Conteúdo sobre alavancas de crescimento...</p>'],
            [4, 'dados-analytics', 'Papel de Dados e Analytics', 3, '<p>Conteúdo sobre dados e analytics...</p>'],
            [4, 'trade-offs', 'Trade-offs entre Volume, Preço e Margem', 4, '<p>Conteúdo sobre trade-offs...</p>'],
            [4, 'simulacoes-decisoes', 'Simulações para Otimizar Decisões', 5, '<p>Conteúdo sobre simulações...</p>'],

            // Capítulo 5
            [5, 'conceito-digital-brain', 'Conceito de Digital Brain', 1, '<p>Conteúdo sobre conceito de Digital Brain...</p>'],
            [5, 'conexao-funcoes', 'Conexão entre Funções da Empresa', 2, '<p>Conteúdo sobre conexão entre funções...</p>'],
            [5, 'ia-reduzir-silos', 'Uso de IA para Reduzir Silos e Acelerar Decisões', 3, '<p>Conteúdo sobre IA para reduzir silos...</p>'],
            [5, 'casos-uso-empresas', 'Casos de Uso em Empresas Globais', 4, '<p>Conteúdo sobre casos de uso...</p>'],
            [5, 'transformacao-digital', 'Caminho para a Transformação Digital', 5, '<p>Conteúdo sobre transformação digital...</p>'],

            // Capítulo 6
            [6, 'mudancas-culturais', 'Mudanças Culturais e Organizacionais', 1, '<p>Conteúdo sobre mudanças culturais...</p>'],
            [6, 'papel-lideranca', 'Papel da Liderança', 2, '<p>Conteúdo sobre papel da liderança...</p>'],
            [6, 'empresas-bem-sucedidas', 'Exemplos de Empresas Bem-Sucedidas', 3, '<p>Conteúdo sobre empresas bem-sucedidas...</p>'],
            [6, 'desafios-comuns', 'Desafios Comuns', 4, '<p>Conteúdo sobre desafios comuns...</p>']
        ];

        $stmt = $this->db->prepare("
            INSERT INTO contents (chapter_id, slug, title, order_index, content) 
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($contents as $content) {
            $stmt->execute($content);
        }
    }

    // Métodos para gerenciar progresso
    public function markAsRead($sessionId, $contentId)
    {
        $stmt = $this->db->prepare("
            INSERT OR REPLACE INTO user_progress (session_id, content_id, is_read, read_at) 
            VALUES (?, ?, 1, CURRENT_TIMESTAMP)
        ");
        return $stmt->execute([$sessionId, $contentId]);
    }

    public function unmarkAsRead($sessionId, $contentId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM user_progress 
            WHERE session_id = ? AND content_id = ?
        ");
        return $stmt->execute([$sessionId, $contentId]);
    }

    public function isRead($sessionId, $contentId)
    {
        $stmt = $this->db->prepare("
            SELECT is_read FROM user_progress 
            WHERE session_id = ? AND content_id = ?
        ");
        $stmt->execute([$sessionId, $contentId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['is_read'] == 1;
    }

    public function getProgress($sessionId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as read_count FROM user_progress 
            WHERE session_id = ? AND is_read = 1
        ");
        $stmt->execute([$sessionId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['read_count'];
    }

    public function getChapterProgress($sessionId, $chapterId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(c.id) as total,
                COUNT(up.content_id) as read_count
            FROM contents c
            LEFT JOIN user_progress up ON c.id = up.content_id AND up.session_id = ? AND up.is_read = 1
            WHERE c.chapter_id = ?
        ");
        $stmt->execute([$sessionId, $chapterId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return round(($result['read_count'] / $result['total']) * 100);
        }
        return 0;
    }

    // Métodos para buscar dados
    public function getAllChapters()
    {
        $stmt = $this->db->query("
            SELECT * FROM chapters 
            ORDER BY number
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChapter($chapterId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM chapters 
            WHERE id = ?
        ");
        $stmt->execute([$chapterId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getChapterByNumber($chapterNumber)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM chapters 
            WHERE number = ?
        ");
        $stmt->execute([$chapterNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getChapterContents($chapterId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM contents 
            WHERE chapter_id = ? 
            ORDER BY order_index
        ");
        $stmt->execute([$chapterId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContent($contentId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, ch.title as chapter_title, ch.number as chapter_number 
            FROM contents c
            JOIN chapters ch ON c.chapter_id = ch.id
            WHERE c.id = ?
        ");
        $stmt->execute([$contentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getContentBySlug($slug)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, ch.title as chapter_title, ch.number as chapter_number 
            FROM contents c
            JOIN chapters ch ON c.chapter_id = ch.id
            WHERE c.slug = ?
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchContents($query)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, ch.title as chapter_title, ch.number as chapter_number 
            FROM contents c
            JOIN chapters ch ON c.chapter_id = ch.id
            WHERE c.title LIKE ? OR c.content LIKE ?
            ORDER BY ch.number, c.order_index
        ");
        $searchTerm = '%' . $query . '%';
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obter próximo conteúdo na sequência
     */
    public function getNextContent($currentContentId, $chapterId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, ch.title as chapter_title, ch.number as chapter_number 
            FROM contents c
            JOIN chapters ch ON c.chapter_id = ch.id
            WHERE c.chapter_id = ? AND c.order_index > (
                SELECT order_index FROM contents WHERE id = ?
            )
            ORDER BY c.order_index
            LIMIT 1
        ");
        $stmt->execute([$chapterId, $currentContentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentReads($sessionId, $limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT c.title, c.slug, ch.title as chapter_title, ch.number as chapter_number, up.read_at
            FROM user_progress up
            JOIN contents c ON up.content_id = c.id
            JOIN chapters ch ON c.chapter_id = ch.id
            WHERE up.session_id = ? AND up.is_read = 1
            ORDER BY up.read_at DESC
            LIMIT ?
        ");
        $stmt->execute([$sessionId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNextUnreadContent($sessionId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, ch.title as chapter_title, ch.number as chapter_number 
            FROM contents c
            JOIN chapters ch ON c.chapter_id = ch.id
            WHERE c.id NOT IN (
                SELECT content_id FROM user_progress 
                WHERE session_id = ? AND is_read = 1
            )
            ORDER BY ch.number, c.order_index
            LIMIT 1
        ");
        $stmt->execute([$sessionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
