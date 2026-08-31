<?php
require_once __DIR__ . '/config.php';

/**
 * Retorna true se houver um usuário logado na sessão.
 */
function estaLogado() {
    return isset($_SESSION['username']);
}

/**
 * Bloqueia o acesso a páginas internas caso o usuário não esteja
 * logado, e força o redirecionamento para a troca de senha caso
 * seja o primeiro acesso dele ao sistema.
 */
function exigirLogin() {
    if (!estaLogado()) {
        header('Location: login.php');
        exit;
    }

    $scriptAtual = basename($_SERVER['SCRIPT_NAME']);
    $paginasLivres = ['trocar_senha.php', 'logout.php'];

    if (($_SESSION['primeiro_acesso'] ?? 'N') === 'S' && !in_array($scriptAtual, $paginasLivres)) {
        header('Location: trocar_senha.php');
        exit;
    }
}

/**
 * Bloqueia o acesso a páginas restritas ao administrador (tipo 'A').
 * Usuários comuns (tipo 'U') são impedidos de criar, editar ou excluir.
 */
function exigirAdmin() {
    exigirLogin();

    if (($_SESSION['tipo'] ?? '') !== 'A') {
        http_response_code(403);
        echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8">'
           . '<title>Acesso negado</title><link rel="stylesheet" href="../style.css"></head>'
           . '<body><div class="container">'
           . '<h1>Acesso negado</h1>'
           . '<p>Seu usuário não tem permissão para realizar esta ação.</p>'
           . '<a href="../index.php" class="btn">Voltar</a>'
           . '</div></body></html>';
        exit;
    }
}

/**
 * Registra uma linha na tabela de logs (Log_Acesso_Usuario).
 */
function registrarLog(PDO $pdo, string $username, string $descricao) {
    $stmt = $pdo->prepare(
        "INSERT INTO Log_Acesso_Usuario (dt_acesso, hr_acesso, descricao, fk_username)
         VALUES (CURDATE(), CURTIME(), ?, ?)"
    );
    $stmt->execute([$descricao, $username]);
}
?>
