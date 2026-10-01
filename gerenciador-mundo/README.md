# Gerenciador Mundo

Sistema web para cadastro e gerenciamento de países, cidades, continentes e governantes, com controle de acesso por usuário.

## Sobre o projeto
O projeto consiste em uma aplicação CRUD em PHP e MySQL para manter dados geográficos e políticos do mundo. O sistema possui login com dois perfis: administrador (pode criar, editar e excluir registros) e usuário comum (sem permissão para alterar dados), além de registro de log de acessos.

## Funcionalidades
- Login com sessão e logout
- Bloqueio do usuário após 3 tentativas de senha incorreta
- Troca obrigatória de senha no primeiro acesso
- Perfis de acesso: administrador e usuário comum
- Cadastro, listagem, edição e exclusão de países
- Cadastro, listagem, edição e exclusão de cidades
- Cadastro, listagem, edição e exclusão de continentes
- Cadastro, listagem, edição e exclusão de governantes
- Registro de logs de acesso dos usuários (trigger no banco)
- Atualização diária automática da idade dos governantes (evento agendado no MySQL)

## Tecnologias utilizadas
- PHP (PDO)
- MySQL
- HTML
- CSS
- Git
- GitHub

## Estrutura do projeto
```
gerenciador-mundo/
├── database/
│   └── bd_mundo.sql      # Criação do banco, dados iniciais, trigger e evento
├── src/
│   ├── paises/           # CRUD de países
│   ├── cidades/          # CRUD de cidades
│   ├── continentes/      # CRUD de continentes
│   ├── governantes/      # CRUD de governantes
│   ├── auth.php          # Controle de sessão e permissões
│   ├── config.php        # Conexão com o banco (lê variáveis de ambiente)
│   ├── login.php         # Tela de login
│   ├── logout.php
│   ├── trocar_senha.php
│   ├── index.php         # Painel principal
│   └── style.css
├── .env.example          # Modelo das variáveis de ambiente
├── README.md
├── LICENSE
└── .gitignore
```

## Requisitos
- PHP 7.4 ou superior
- MySQL ou MariaDB (pode ser via XAMPP)
- Git

## Como executar
1. Clone o repositório.
2. Acesse a pasta do projeto.
3. Importe o banco de dados (cria o banco `bd_mundo`):
```
mysql -u root -p < database/bd_mundo.sql
```
4. Configure as variáveis de ambiente, usando `.env.example` como modelo. Se nada for definido, o sistema usa o padrão local do XAMPP (`root`, sem senha):
   `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`.
5. Inicie o servidor:
```
php -S localhost:8000 -t src
```
6. Acesse `http://localhost:8000` e entre com um dos usuários de teste criados no script do banco (`adm_tst` ou `usuario_tst`). A senha inicial está no próprio `bd_mundo.sql` e deve ser trocada no primeiro acesso.

Alternativa: copie a pasta `src` para o `htdocs` do XAMPP.

Para o evento diário de idade funcionar, o MySQL precisa estar com o `event_scheduler` ativo (o script tenta ativá-lo, o que exige permissão de administrador).

## Autor
Gabriela Gallo