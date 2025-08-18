# o9 Learning Hub

Uma plataforma de estudos moderna e interativa para aprender sobre planejamento de demanda, gestão de fornecedores, IBP, RGM, Digital Brain e transformação digital.

## 🚀 Características

- **Conteúdo Estruturado**: 6 capítulos organizados com 35 conteúdos específicos
- **Sistema de Progresso**: Acompanhe seu progresso de leitura com persistência em SQLite
- **Interface Moderna**: Design responsivo com Tailwind CSS
- **Navegação Intuitiva**: Menu hierárquico com busca em tempo real
- **Arquitetura Dinâmica**: Sistema baseado em templates únicos para máxima eficiência
- **Tecnologias**: PHP, SQLite, Tailwind CSS, Font Awesome

## 📁 Estrutura do Projeto

```
site/
├── index.php                 # Página inicial
├── content.php              # Template único para conteúdos (usado com ?slug=)
├── chapter.php              # Template único para capítulos (usado com ?chapter=)
├── config/
│   └── database.php         # Configuração do banco SQLite
├── includes/
│   ├── functions.php        # Funções utilitárias
│   ├── header.php           # Header e sidebar
│   └── footer.php           # Footer e JavaScript
├── scripts/
│   ├── reset_database.php   # Reset do banco de dados
│   ├── populate_content.php # Popula conteúdo inicial
│   └── populate_all_content.php # Popula todo o conteúdo real
└── data/
    └── o9_learning.db       # Banco de dados SQLite
```

## 🗄️ Banco de Dados

### Tabelas

1. **chapters**: Informações dos capítulos
   - `id`, `number`, `title`, `icon`, `color`, `description`

2. **contents**: Conteúdos específicos
   - `id`, `chapter_id`, `slug`, `title`, `content`, `order_index`

3. **user_progress**: Progresso do usuário
   - `id`, `session_id`, `content_id`, `is_read`, `read_at`

## 🛠️ Instalação e Configuração

### Pré-requisitos
- PHP 7.4+
- Extensão SQLite3 habilitada

### Passos

1. **Clone o repositório**
   ```bash
   git clone <repository-url>
   cd site
   ```

2. **Configure permissões**
   ```bash
   chmod 755 data/
   chmod 644 data/o9_learning.db
   ```

3. **Inicialize o banco de dados**
   ```bash
   php scripts/reset_database.php
   ```

4. **Popule o conteúdo real**
   ```bash
   php scripts/populate_all_content.php
   ```

5. **Inicie o servidor PHP**
   ```bash
   php -S localhost:8000
   ```

6. **Acesse no navegador**
   ```
   http://localhost:8000
   ```

## 🎯 Como Usar

### Navegação
- **Menu Lateral**: Navegue pelos capítulos e conteúdos
- **Busca**: Use o campo de busca para encontrar conteúdos específicos
- **Progresso**: Visualize seu progresso geral e por capítulo

### URLs Dinâmicas
- **Capítulos**: `chapter.php?chapter=1`
- **Conteúdos**: `content.php?slug=fundamentos`

### Sistema de Progresso
- **Marcar como Lido**: Clique no botão verde no final de cada conteúdo
- **Desmarcar**: Clique no botão cinza para desmarcar
- **Progresso Persistente**: Seu progresso é salvo automaticamente

## 🔧 Arquitetura Dinâmica

### Templates Únicos
- **content.php**: Renderiza qualquer conteúdo baseado no parâmetro `slug`
- **chapter.php**: Renderiza qualquer capítulo baseado no parâmetro `chapter`

### Vantagens
- ✅ **Manutenção Simplificada**: Apenas 2 templates para todo o site
- ✅ **Escalabilidade**: Adicionar novos conteúdos é automático
- ✅ **Performance**: Menos arquivos PHP para carregar
- ✅ **Consistência**: Interface uniforme em todas as páginas

### Sistema de URLs
```
# Capítulos
http://localhost:8000/chapter.php?chapter=1
http://localhost:8000/chapter.php?chapter=2

# Conteúdos
http://localhost:8000/content.php?slug=fundamentos
http://localhost:8000/content.php?slug=gestao-riscos
```

## 📚 Conteúdos Disponíveis

### Capítulo 1: Planejamento de Demanda
- Fundamentos do Planejamento de Demanda
- Estrutura e Hierarquia do Plano de Demanda
- Métodos Estatísticos, Agregação e Desagregação
- Riscos e Oportunidades
- Precisão de Forecast e o Cockpit de Análise
- Dificuldades Práticas no Varejo
- Abordagens Modernas: Ágil, Digital Twins e IA

### Capítulo 2: Gestão de Relacionamento com Fornecedores
- Gestão de Riscos com Fornecedores
- Métricas-Chave: Tempo de Reação e Tempo de Resolução
- Colaboração com Fornecedores: Muito Além do Básico
- Visibilidade em Múltiplos Níveis (Tiers)
- Principais Erros de Percepção sobre Risco
- Casos Práticos
- IA e GenAI na Antecipação de Riscos
- Segmentação e Onboarding de Fornecedores

### Capítulo 3: Integrated Business Planning (IBP)
- Diferença entre S&OP e IBP
- Alinhamento das Áreas da Empresa
- Ciclo de IBP
- Colaboração Cross-Functional e Simulações
- Benefícios do IBP

### Capítulo 4: Revenue Growth Management (RGM)
- Estratégias de Precificação, Promoções e Sortimento
- Identificação de Alavancas de Crescimento
- Papel de Dados e Analytics
- Trade-offs entre Volume, Preço e Margem
- Simulações para Otimizar Decisões

### Capítulo 5: Plataforma o9 Digital Brain
- Conceito de Digital Brain
- Conexão entre Funções da Empresa
- Uso de IA para Reduzir Silos e Acelerar Decisões
- Casos de Uso em Empresas Globais
- Caminho para a Transformação Digital

### Capítulo 6: Transformação Digital
- Mudanças Culturais e Organizacionais
- Papel da Liderança
- Exemplos de Empresas Bem-Sucedidas
- Desafios Comuns

## 🎨 Personalização

### Cores dos Capítulos
Cada capítulo tem sua cor específica definida no banco de dados:
- Capítulo 1: Azul (`blue`)
- Capítulo 2: Verde (`green`)
- Capítulo 3: Roxo (`purple`)
- Capítulo 4: Laranja (`orange`)
- Capítulo 5: Índigo (`indigo`)
- Capítulo 6: Verde-azulado (`teal`)

### Adicionar Novos Conteúdos
1. Insira o registro no banco de dados
2. O conteúdo aparecerá automaticamente na navegação
3. Use `content.php?slug=novo-slug` para acessar

## 🔍 Funcionalidades Avançadas

### Sessões
- Cada usuário tem um `session_id` único
- Progresso persistente entre sessões
- Múltiplos usuários podem usar simultaneamente

### Performance
- Banco SQLite otimizado para leitura
- Templates únicos reduzem carga do servidor
- JavaScript otimizado para busca em tempo real

### SEO
- Meta tags `noindex` em todas as páginas
- URLs amigáveis e semânticas
- Estrutura HTML semântica

## 🚀 Próximos Passos

- [ ] Adicionar mais conteúdos aos capítulos restantes
- [ ] Implementar sistema de favoritos
- [ ] Adicionar funcionalidade de notas
- [ ] Criar sistema de certificados
- [ ] Implementar analytics de uso

## 📄 Licença

Este projeto é para uso educacional e interno.
