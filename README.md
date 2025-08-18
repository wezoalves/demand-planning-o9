# o9 Learning Hub

Plataforma de aprendizado sobre planejamento de demanda e gestão de fornecedores.

## Estrutura do Projeto

```
o9.com/
├── index.php          # Proxy principal (ponto de entrada)
├── .htaccess          # Configurações do Apache
├── README.md          # Documentação
├── book/              # Conteúdo dos capítulos (Markdown)
└── site/              # Aplicação principal
    ├── index.php      # Página inicial
    ├── dashboard.php  # Dashboard de progresso
    ├── content.php    # Visualizador de conteúdo
    ├── chapter.php    # Lista de capítulos
    ├── admin.php      # Painel administrativo
    ├── style.css      # Estilos CSS
    ├── config/        # Configurações
    ├── includes/      # Arquivos incluídos
    ├── scripts/       # Scripts de população
    └── data/          # Banco de dados SQLite
```

## Configuração da Hospedagem

### Estrutura de Arquivos
- **Raiz**: Contém apenas o proxy (`index.php`) e configurações
- **Pasta `site/`**: Contém toda a aplicação

### Funcionamento do Proxy
O arquivo `index.php` na raiz:
1. Captura todas as requisições
2. Redireciona para a pasta `site/`
3. Serve arquivos estáticos (CSS, JS, imagens)
4. Executa arquivos PHP da aplicação

### URLs Funcionais
- `/` → `site/index.php`
- `/dashboard` → `site/dashboard.php`
- `/content.php?slug=...` → `site/content.php?slug=...`
- `/style.css` → `site/style.css`

## Tecnologias

- **Backend**: PHP 8.2+
- **Banco de Dados**: SQLite
- **Frontend**: HTML5, CSS3, JavaScript
- **Design**: shadcn/ui inspired
- **Ícones**: FontAwesome 6

## Funcionalidades

- ✅ Sistema de progresso com persistência
- ✅ Dashboard de aprendizado
- ✅ Navegação por capítulos e conteúdos
- ✅ Sidebar responsivo
- ✅ Footer fixo com controles
- ✅ Sistema de busca
- ✅ Design responsivo

## Instalação

1. Fazer upload dos arquivos para a raiz da hospedagem
2. Garantir que a pasta `site/data/` tenha permissões de escrita
3. Acessar a URL principal
4. O sistema criará automaticamente o banco de dados

## Desenvolvimento Local

```bash
cd site
php -S localhost:8000
```

Acesse: http://localhost:8000
