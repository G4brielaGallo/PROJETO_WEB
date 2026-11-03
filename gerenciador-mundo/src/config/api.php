<?php
// Chave da REST Countries (API v5, autenticação por Bearer token).
// Defina a variável de ambiente REST_COUNTRIES_KEY (veja o .env.example).
// "rc_live_demo" é uma chave de demonstração: retorna dados limitados e
// não deve alimentar o banco com dados de produção. Para o exercício
// completo, obtenha uma chave própria no serviço.
//
// Este arquivo é executado no servidor: a chave NUNCA é enviada ao navegador.
$apiKey = getenv('REST_COUNTRIES_KEY') ?: 'rc_live_demo';
?>
