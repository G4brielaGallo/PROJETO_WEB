/*localStorage.setItem("nome", "André");
const nome = localStorage.getItem("nome");
console.log(nome); // "André"
localStorage.removeItem("nome");
localStorage.clear;

const usuario = JSON.parse(localStorage.getItem("usuario"));*/

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
    },
    9786525935775: {
        titulo: "A Misteriosa Loja De Penhores",
        preco: 49.90,
        autor: "Go Suyoo"
    }
};

//Define as variáveis para área de compra, área de seleção de produtos, filtro, área do carrinho, carrinho e área de pagamento
const areaCompra = document.querySelector(".areaCompra");
const areaSelecionarProdutos = document.querySelector(".areaSelecionarProdutos");
const filtro = document.getElementById("filtro");
const areaCarrinho = document.querySelector(".areaCarrinho"); //div contendo as divs .carrinho e .areaPagamento
const carrinho = document.querySelector(".carrinho");
const areaPagamento = document.querySelector(".areaPagamento");

let carrinhoLista = []; //Array para armazenar os produtos no carrinho

const livros = Object.entries(objLivros);

//Cria e retorna a divLivro, com os elementos gerais dos livros no DOM
function criarDivLivroGeral(livro, isbn) {
    const tituloLivro = document.createElement("p");
    tituloLivro.innerHTML = livro.titulo;
    tituloLivro.className = "tituloLivro";

    const autorLivro = document.createElement("p");
    autorLivro.innerHTML = "por " + livro.autor;
    autorLivro.className = "autorLivro";

    const precoLivro = document.createElement("p");
    precoLivro.innerHTML = "R$" + livro.preco.toFixed(2).replace(".", ",");
    precoLivro.className = "precoLivro";

    const isbnLivro = document.createElement("p");
    isbnLivro.innerHTML = "ISBN: " + isbn;
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
    salvarCarrinhoLocalStorage();
}

//Realiza a atualização do carrinho
function renderizarCarrinho() {
    carrinho.innerHTML = "";

    //Para cada item do carrinho puxa e exibe suas informações no DOM + o botão de remover
    for (const item of carrinhoLista) {
        const divProdutoCarrinho = document.createElement("div");
        divProdutoCarrinho.className = "cardLivro";

        divProdutoCarrinho.innerHTML = `
            <p class="tituloLivro">${item.titulo}</p>
            <p class="autorLivro">por ${item.autor}</p>
            <p class="qntProduto">Quantidade: ${item.qnt}</p>
            <p class="precoLivro">Preço Unitário: R$ ${(item.preco).toFixed(2).replace(".", ",")}</p>
            <p class="totalQntLivros">Total: R$ ${(item.preco * item.qnt).toFixed(2).replace(".", ",")}</p>
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

            renderizarCarrinho();
            salvarCarrinhoLocalStorage();
        }

        divProdutoCarrinho.appendChild(botaoRemover);
        carrinho.appendChild(divProdutoCarrinho);
    }

    //Calcula quantidade total de itens no carrinho
    cont = 0;

    carrinhoLista.forEach(item => {
        cont += item.qnt;
    });

    //Mostra o total de itens que estão no carrinho
    const qntItensCarrinho = document.getElementById("qntItensCarrinho");
    qntItensCarrinho.innerHTML = "Total de itens no carrinho: " + cont;

    calcularTotalCompra();

    //Se não tiver itens no carrinho exibe mensagem
    if (carrinhoLista.length === 0) {
        carrinho.innerHTML = `<p class="tituloLivro">Nenhum item adicionado ao carrinho ainda</p>`;
        return;
    }
}

//Faz o calculo do valor total a ser pago pelos produtos e suas quantidades e exibe
function calcularTotalCompra() {
    const totalCompra = document.getElementById("totalCompra");

    let valorTotal = 0;

    for (const item of carrinhoLista) {
        valorTotal = valorTotal + (item.preco * item.qnt);
    }
    totalCompra.innerHTML = "";
    totalCompra.innerHTML = `R$ ${valorTotal.toFixed(2).replace(".", ",")}`;
}

//Salva no localStorage o isbn e quantidade do produto que está no carrinho
function salvarCarrinhoLocalStorage() {
    const carrinhoSimplificado = carrinhoLista.map(item => ({
        isbn: item.isbn,
        qnt: item.qnt
    }));

    localStorage.setItem("carrinho", JSON.stringify(carrinhoSimplificado));
}

//Pega os valores no localStorage, caso tenha algo puxa as informações do produto correspondente e exibe no carrinho
function carregarCarrinho() {
    const carrinhoSalvo = JSON.parse(localStorage.getItem("carrinho"));

    if (carrinhoSalvo) {
        carrinhoLista = carrinhoSalvo.map(item => {
            const livro = objLivros[item.isbn];

            return {
                isbn: item.isbn,
                titulo: livro.titulo,
                autor: livro.autor,
                preco: livro.preco,
                qnt: item.qnt
            };
        });

        cont = 0;

        //Calcula quantidade total de itens no carrinho
        carrinhoLista.forEach(item => {
            cont += Number(item.qnt);
        });

        renderizarCarrinho();
        calcularTotalCompra();
    }
}

//Sempre que o valor do select mudar, chama a função aplicarFiltro passando o valor selecionado
filtro.addEventListener("change", function () {
    aplicarFiltro(this.value);
});

//Aplica o filtro nos livros com base no valor selecionado pelo usuário
function aplicarFiltro(valor) {
    let livrosFiltrados;

    //Verifica qual filtro foi escolhido
    switch (valor) {
        //Filtra livros com preço maior ou igual a 50
        case "acima50":
            livrosFiltrados = livros.filter(([isbn, livro]) => livro.preco >= 50);
            break;
        //Filtra livros com preço menor ou igual a 50
        case "ate50":
            livrosFiltrados = livros.filter(([isbn, livro]) => livro.preco <= 50);
            break;
        //Caso "todos" ou qualquer outro valor, retorna todos os livros sem filtro
        default:
            livrosFiltrados = livros;
    }

    renderizarLivros(livrosFiltrados);
}

//Preenche a área de selecionar produtos com todos os livros do array livrosFiltrados/livros
function renderizarLivros(lista) {
    areaSelecionarProdutos.innerHTML = "";

    for (let i = 0; i < lista.length; i++) {
        const [isbn, livro] = lista[i];

        const divLivro = criarDivLivroGeral(livro, isbn);

        const coluna = document.createElement("div");
        coluna.className = "col-lg-4 col-md-6 col-12";

        coluna.appendChild(divLivro);

        areaSelecionarProdutos.appendChild(coluna);

        const botaoAdicionarCarrinho = document.createElement("button");
        botaoAdicionarCarrinho.innerHTML = "ADICIONAR AO CARRINHO";
        botaoAdicionarCarrinho.className = "btnAdicionarCarrinho";
        botaoAdicionarCarrinho.onclick = function () {
            adicionarLivroCarrinho(livro, isbn);
        }

        divLivro.appendChild(botaoAdicionarCarrinho);
    }
}

//Vai renderizar todos os livros e carregar o carrinho
renderizarLivros(livros);
carregarCarrinho();

//Define que ao clicar no botão de pagar, envia aviso que botão de pagar é apenas decorativo
const botaoPagar = document.querySelector(".btnPagar");
botaoPagar.onclick = function () {
    alert("Este é um botão decorativo!!!");
}