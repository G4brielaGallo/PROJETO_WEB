# Loja Carrinho de Compras

Aplicação web de uma livraria virtual (Livraria Panacea) com catálogo de livros, filtro por faixa de preço e carrinho de compras.

## Sobre o projeto
O projeto simula a vitrine de uma livraria online. O usuário consulta os livros disponíveis, filtra por preço, adiciona itens ao carrinho e acompanha a quantidade de itens e o valor total da compra. O carrinho é mantido no navegador, então não se perde ao recarregar a página.

## Funcionalidades
- Listagem de livros com título, autor e preço
- Filtro de livros (todos, até R$ 50, acima de R$ 50)
- Adição de livros ao carrinho, com controle de quantidade
- Remoção de itens do carrinho
- Cálculo do total de itens e do valor total da compra
- Persistência do carrinho no navegador (localStorage)

O botão "PAGAR" é apenas ilustrativo: não há processamento de pagamento.

## Tecnologias utilizadas
- HTML
- CSS
- JavaScript
- Bootstrap 5 (via CDN)
- Git
- GitHub

## Estrutura do projeto
```
loja-carrinho-compras/
├── src/
│   ├── index.html        # Página principal
│   ├── css/estilos.css   # Estilos da página
│   └── js/carrinho.js    # Catálogo, filtro e lógica do carrinho
├── README.md
├── LICENSE
└── .gitignore
```

## Requisitos
- Navegador atualizado
- Conexão com a internet (o Bootstrap é carregado por CDN)

## Como executar
1. Clone o repositório.
2. Acesse a pasta `src`.
3. Abra o arquivo `index.html` no navegador.

## Autor
Gabriela Gallo
