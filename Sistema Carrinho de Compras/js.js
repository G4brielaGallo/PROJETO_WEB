localStorage.setItem("nome", "André");
const nome = localStorage.getItem("nome");
console.log(nome); // "André"
localStorage.removeItem("nome");
localStorage.clear;

const usuario = JSON.parse(localStorage.getItem("usuario"));

let cont = 0;

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

const areaCompra = document.querySelector(".areaCompra");

const livros = Object.entries(objLivros);

for (i = 0; i < livros.length; i++) {
    const [isbn, livro] = livros[i];

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

    const botaoComprar = document.createElement("button");
    botaoComprar.innerHTML = "COMPRAR";
    botaoComprar.className = "btnComprar";
    botaoComprar.onclick = function comprarLivro() {
        cont++;
    }

    const divLivro = document.createElement("div");
    divLivro.className = "cardLivro";
    
    areaCompra.appendChild(divLivro);
    divLivro.appendChild(tituloLivro);
    divLivro.appendChild(autorLivro);
    divLivro.appendChild(precoLivro);
    divLivro.appendChild(isbnLivro);
    divLivro.appendChild(botaoComprar);
}