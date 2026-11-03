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
- Importação de países pela API REST Countries (GET via cURL + INSERT via PDO, com validação da resposta e checagem de duplicidade pelo código ISO)
- Mapa interativo das cidades com a API do Google Maps (mapa geral e por cidade, com latitude/longitude cadastráveis)
- Registro de logs de acesso dos usuários (trigger no banco)
- Atualização diária automática da idade dos governantes (evento agendado no MySQL)

## Tecnologias utilizadas
- PHP (PDO)
- Google Maps JavaScript API
- REST Countries API v5 (cURL e JSON)
- MySQL
- HTML
- CSS
- Git
- GitHub

## Estrutura do projeto
```
gerenciador-mundo/
├── database/
│   ├── bd_mundo.sql      # Criação do banco, dados iniciais, trigger e evento
│   ├── migracao_maps.sql      # Bancos já existentes: latitude/longitude em Cidades
│   └── migracao_countries.sql # Bancos já existentes: ISO, capital e sub-região em Países
├── src/
│   ├── paises/           # CRUD de países + importar.php / importar_pais.php (REST Countries)
│   ├── cidades/          # CRUD de cidades
│   ├── continentes/      # CRUD de continentes
│   ├── governantes/      # CRUD de governantes
│   ├── config/
│   │   ├── google_maps.php  # Chave da API do Google Maps
│   │   └── api.php          # Chave da REST Countries
│   ├── mapa.php          # Mapa interativo (Google Maps)
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
- Chave da Maps JavaScript API (Google Cloud)
- Extensões `curl` e `pdo_mysql` habilitadas no PHP
- Chave da REST Countries (ou a chave de demonstração `rc_live_demo`)

## Como executar
1. Clone o repositório.
2. Acesse a pasta do projeto.
3. Importe o banco de dados (cria o banco `bd_mundo`):
```
mysql -u root -p < database/bd_mundo.sql
```
   Se você já tinha o banco `bd_mundo`, em vez disso execute `database/migracao_maps.sql` e `database/migracao_countries.sql`.
4. Configure as variáveis de ambiente, usando `.env.example` como modelo. Se nada for definido, o sistema usa o padrão local do XAMPP (`root`, sem senha):
   `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `GOOGLE_MAPS_KEY`, `REST_COUNTRIES_KEY`.
5. Inicie o servidor:
```
php -S localhost:8000 -t src
```
6. Acesse `http://localhost:8000` e entre com um dos usuários de teste criados no script do banco (`adm_tst` ou `usuario_tst`). A senha inicial está no próprio `bd_mundo.sql` e deve ser trocada no primeiro acesso.

### Chave da API do Google Maps
1. Acesse o [Google Cloud Console](https://console.cloud.google.com/) e crie um projeto (ex.: CRUD Mundo).
2. Configure uma conta de faturamento (exigência do Google Maps Platform).
3. Em *APIs e serviços > Biblioteca*, habilite a **Maps JavaScript API**.
4. Em *APIs e serviços > Credenciais*, crie uma chave de API e restrinja-a aos endereços HTTP autorizados (ex.: `localhost`) e à Maps JavaScript API.
5. Informe a chave na variável de ambiente `GOOGLE_MAPS_KEY` ou, só para testes locais, em `src/config/google_maps.php`.

A chave do Maps JavaScript fica visível no navegador; por isso a restrição por domínio é importante. Não envie chaves reais ao GitHub.

Com o servidor rodando, acesse `http://localhost:8000/mapa.php` (todas as cidades) ou `http://localhost:8000/mapa.php?id=1` (uma cidade).

### Importação de países (REST Countries)
Como administrador, acesse *Países > Importar da API* (`/paises/importar.php`) e digite o nome do país **em inglês** (ex.: `Argentina`). O fluxo tem dois processos distintos: o PHP faz um **GET** na API (somente consulta) e, depois de validar o JSON, executa um **INSERT** no MySQL com PDO.

Pontos importantes:
- A chave `rc_live_demo` retorna dados de demonstração/limitados; para testar vários países, use uma chave própria em `REST_COUNTRIES_KEY`.
- O código ISO (`alpha_2`) identifica o país externamente; `pk_pais` continua sendo a chave primária interna. Importar um país já existente mostra "Este país já está cadastrado".
- A API não fornece área, idioma, clima, regime político, moeda nem governante. Esses campos ficam vazios (`NULL`) nos países importados e podem ser preenchidos em *Editar*.
- A região da API (`Americas`, `Asia`, `Africa`, `Europe`, `Oceania`, `Antarctic`) é convertida para o continente correspondente já cadastrado.
- Somente a primeira capital retornada é armazenada. A rotina atual só faz INSERT; não há sincronização/UPDATE automático.

Alternativa: copie a pasta `src` para o `htdocs` do XAMPP.

Para o evento diário de idade funcionar, o MySQL precisa estar com o `event_scheduler` ativo (o script tenta ativá-lo, o que exige permissão de administrador).

## Autor
Gabriela Gallo