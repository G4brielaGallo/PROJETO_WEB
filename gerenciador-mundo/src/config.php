<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Credenciais lidas de variáveis de ambiente (nunca escreva senhas no código).
// Se a variável não existir, usa o padrão local do XAMPP.
$host     = getenv('DB_HOST') ?: 'localhost';
$dbname   = getenv('DB_NAME') ?: 'bd_mundo';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
