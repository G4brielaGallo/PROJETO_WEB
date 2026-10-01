# Sistema de Notas da Turma

Aplicação web em PHP que calcula médias, situação dos alunos e estatísticas gerais de uma turma a partir das notas informadas.

## Sobre o projeto
O usuário informa o nome da turma e a quantidade de alunos. O sistema gera os campos de cadastro e, para cada aluno (duas notas e um trabalho), calcula os resultados individuais e um relatório estatístico da turma.

## Funcionalidades
- Formulário dinâmico conforme a quantidade de alunos informada
- Validação das notas (0 a 10) no formulário
- Cálculo da média de cada aluno
- Cálculo da raiz quadrada da soma das notas
- Cálculo da diferença absoluta entre a maior e a menor nota
- Classificação em Aprovado (média ≥ 7), Recuperação (média ≥ 5) ou Reprovado
- Estatísticas da turma: média geral, maior e menor média, soma total das notas e percentual de aprovação
- Mensagem de desempenho geral (meta de 70% de aprovação)
- Layout responsivo, com tabela de rolagem horizontal em telas pequenas

## Tecnologias utilizadas
- PHP
- HTML
- CSS
- Git
- GitHub

## Estrutura do projeto
```
sistema-notas-turma/
├── src/
│   ├── index.php          # Cadastro da turma e dos alunos
│   ├── processamento.php  # Cálculos e relatório final
│   └── css/estilos.css    # Estilos
├── README.md
├── LICENSE
└── .gitignore
```

## Requisitos
- PHP 7.4 ou superior (ou XAMPP)
- Git

## Como executar
1. Clone o repositório.
2. Acesse a pasta do projeto.
3. Inicie o servidor embutido do PHP:
```
php -S localhost:8000 -t src
```
4. Abra `http://localhost:8000` no navegador.

Alternativa: copie a pasta `src` para o `htdocs` do XAMPP e acesse pelo Apache.

## Autor
Gabriela Gallo
