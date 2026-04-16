localStorage.setItem("nome", "André");
const nome = localStorage.getItem("nome");
console.log(nome); // "André"
localStorage.removeItem("nome");
localStorage.clear;

const usuario = JSON.parse(localStorage.getItem("usuario"));

let cont = 0; //Contador para o Total de Produtos que estão no Carrinho

//Cria array de objetos (objLivros)
const objLivros = {
    9786555320947: {
        titulo: "O Conto da Aia",
        preco: 89.90,
        autor: "Margaret Atwood"
    },
    9788571645400: {
        titulo: "O Dia do Curinga",
        preco: 74.90,
        autor: "Jostein Gaarder"
    },
    9788594541475: {
        titulo: "Lady Killers",
        preco: 109.90,
        autor: "Tori Telfer"
    }
};

//Define as variáveis para área de compra e o carrinho
const areaCompra = document.querySelector(".areaCompra");
const carrinho = document.querySelector(".carrinho");

let carrinhoLista = []; //Array para armazenar os produtos no carrinho

const livros = Object.entries(objLivros);

//Cria e retorna a divLivro, com os elementos gerais dos livros no DOM
function criarDivLivroGeral(livro, isbn) {
    const tituloLivro = document.createElement("p");
    tituloLivro.innerHTML = livro.titulo;
    tituloLivro.className = "tituloLivro";

    const autorLivro = document.createElement("p");
    autorLivro.innerHTML = livro.autor;
    autorLivro.className = "autorLivro";

    const precoLivro = document.createElement("p");
    precoLivro.innerHTML = livro.preco;
    precoLivro.className = "precoLivro";

    const isbnLivro = document.createElement("p");
    isbnLivro.innerHTML = isbn;
    isbnLivro.className = "isbnLivro";

    const divLivro = document.createElement("div");
    divLivro.className = "cardLivro";

    divLivro.appendChild(tituloLivro);
    divLivro.appendChild(autorLivro);
    divLivro.appendChild(precoLivro);
    divLivro.appendChild(isbnLivro);

    return divLivro;
}

//Adiciona um produto ao carrinho e o atualiza para o usuário
function adicionarLivroCarrinho(livro, isbn) {
    const itemExistente = carrinhoLista.find(item => item.isbn == isbn);

    if (itemExistente) {
        itemExistente.qnt++;
    }
    else {
        carrinhoLista.push({
            isbn: isbn,
            titulo: livro.titulo,
            autor: livro.autor,
            preco: livro.preco,
            qnt: 1
        });
    }

    renderizarCarrinho();
}

//Realiza a atualização do carrinho
function renderizarCarrinho() {
    carrinho.innerHTML = "";

    for (const item of carrinhoLista) {
        const divProdutoCarrinho = document.createElement("div");

        divProdutoCarrinho.innerHTML = `
            <p>${item.titulo}</p>
            <p>por ${item.autor}</p>
            <p>Quantidade: ${item.qnt}</p>
            <p>Preço Unitário: ${item.preco}</p>
            <p>Total: R$ ${item.preco * item.qnt}</p>
        `;

        const botaoRemover = document.createElement("button");
        botaoRemover.innerHTML = "REMOVER";
        botaoRemover.className = "btnRemover";

        //Verifica se já existe o item e sua quantidade, se maior que 1, diminuí, se igual a 1 remove do carrinho
        botaoRemover.onclick = function () {
            const itemExistente = carrinhoLista.find(i => i.isbn == item.isbn);

            if (itemExistente.qnt > 1) {
                itemExistente.qnt--;
            }
            else {
                carrinhoLista = carrinhoLista.filter(i => i.isbn !== item.isbn);
            }

            cont--;
            renderizarCarrinho();
        }

        divProdutoCarrinho.appendChild(botaoRemover);
        carrinho.appendChild(divProdutoCarrinho);
    }
}

//Preenche a área de compras com todos os livros do array livros
for (let i = 0; i < livros.length; i++) {
    const [isbn, livro] = livros[i];

    const divLivro = criarDivLivroGeral(livro, isbn);

    areaCompra.appendChild(divLivro);

    const botaoComprar = document.createElement("button");
    botaoComprar.innerHTML = "COMPRAR";
    botaoComprar.className = "btnComprar";
    botaoComprar.onclick = function () {
        adicionarLivroCarrinho(livro, isbn);
        cont++;
    }

    divLivro.appendChild(botaoComprar);
}