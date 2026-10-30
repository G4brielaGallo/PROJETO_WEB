<?php
// Chave de acesso à API do Google Maps (Maps JavaScript API).
// Defina a variável de ambiente GOOGLE_MAPS_KEY (veja o .env.example)
// ou substitua o texto "SUA_CHAVE_API" abaixo apenas para testes locais.
//
// ATENÇÃO: a chave do Maps JavaScript fica visível no navegador. Por isso,
// no Google Cloud, restrinja-a aos domínios autorizados (ex.: localhost) e
// somente à Maps JavaScript API.
$googleMapsKey = getenv('GOOGLE_MAPS_KEY') ?: 'SUA_CHAVE_API';
?>
