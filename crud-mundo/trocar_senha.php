<?php
require_once 'auth.php';

if (!estaLogado()) {
    header('Location: login.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaAtual = $_POST['senha_atual'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmaSenha = $_POST['confirma_senha'] ?? '';

    $stmt = $pdo->prepare("SELECT senha FROM Usuarios WHERE pk_username = ?");
    $stmt->execute([$_SESSION['username']]);
    $hashAtual = $stmt->fetchColumn();

    if (!password_verify($senhaAtual, $hashAtual)) {
        $erro = 'Senha atual incorreta.';
    } elseif (strlen($novaSenha) < 6) {
        $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
    } elseif ($novaSenha !== $confirmaSenha) {
        $erro = 'A confirmação de senha não confere.';
    } elseif ($novaSenha === $senhaAtual) {
        $erro = 'A nova senha deve ser diferente da senha atual.';
    } else {
        $novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE Usuarios SET senha = ?, primeiro_acesso = 'N' WHERE pk_username = ?")
            ->execute([$novoHash, $_SESSION['username']]);

        registrarLog($pdo, $_SESSION['username'], 'Senha alterada pelo usuário');

        $_SESSION['primeiro_acesso'] = 'N';
        $sucesso = 'Senha alterada com sucesso! Redirecionando...';
        header('Refresh: 2; URL=index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar Senha - Sistema de Gerenciamento Mundial</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container fade-in" style="max-width: 460px; margin: 60px auto;">
        <h1>🔑 Trocar Senha</h1>

        <?php if (($_SESSION['primeiro_acesso'] ?? 'N') === 'S' && !$sucesso): ?>
            <div class="alert alert-warning">
                Este é o seu primeiro acesso ao sistema. Por segurança, você precisa
                definir uma nova senha antes de continuar.
            </div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <?php if (!$sucesso): ?>
        <form method="POST" action="trocar_senha.php">
            <div class="form-group">
                <label>Senha Atual<span class="required">*</span></label>
                <input type="password" name="senha_atual" required>
            </div>
            <div class="form-group">
                <label>Nova Senha<span class="required">*</span></label>
                <input type="password" name="nova_senha" required minlength="6">
                <span class="help-text">Mínimo de 6 caracteres.</span>
            </div>
            <div class="form-group">
                <label>Confirmar Nova Senha<span class="required">*</span></label>
                <input type="password" name="confirma_senha" required minlength="6">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Salvar Nova Senha</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
