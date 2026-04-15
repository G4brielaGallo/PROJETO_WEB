localStorage.setItem("nome","André");
const nome = localStorage.getItem("nome");
console.log(nome); // "André"
localStorage.removeItem("nome");
localStorage.clear;

const usuario = JSON.parse(localStorage.getItem("usuario"));

const objLivros = {
    9786555320947: {
        titulo: "O Conto da Aia",
        preco: 89.90,
        autor: "Margaret Atwood"
    },
    ISBN2: {
        nome: "O Dia do Curinga",
        preco: 74.90,
        autor: "Jostein Gaarder"
    },
    9788594541475: {
        nome: "Lady Killers",
        preco: 109.90,
        autor: "Tori Telfer"
    }
};