<?php
require_once 'auth.php';

if (estaLogado()) {
    header('Location: index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($username === '' || $senha === '') {
        $erro = 'Preencha usuário e senha.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM Usuarios WHERE pk_username = ?");
        $stmt->execute([$username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $erro = 'Usuário ou senha inválidos.';
        } elseif ($usuario['status'] === 'B') {
            $erro = 'Usuário bloqueado após 3 tentativas de senha incorreta. Procure o administrador do sistema.';
        } else {
            $senhaCorreta = false;

            // Compatibilidade com as senhas de seed (texto puro).
            // Se a senha ainda não estiver em formato de hash, valida
            // por igualdade simples e converte para hash seguro.
            if (password_get_info($usuario['senha'])['algo'] === null) {
                if (hash_equals($usuario['senha'], $senha)) {
                    $senhaCorreta = true;
                    $novoHash = password_hash($senha, PASSWORD_DEFAULT);
                    $pdo->prepare("UPDATE Usuarios SET senha = ? WHERE pk_username = ?")
                        ->execute([$novoHash, $username]);
                }
            } else {
                $senhaCorreta = password_verify($senha, $usuario['senha']);
            }

            if ($senhaCorreta) {
                // Zera as tentativas e incrementa qtd_acesso
                $pdo->prepare("UPDATE Usuarios SET tentativas_login = 0, qtd_acesso = qtd_acesso + 1 WHERE pk_username = ?")
                    ->execute([$username]);

                registrarLog($pdo, $username, 'Login realizado com sucesso');

                session_regenerate_id(true);
                $_SESSION['username'] = $usuario['pk_username'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['tipo'] = $usuario['tipo'];
                $_SESSION['primeiro_acesso'] = $usuario['primeiro_acesso'];

                if ($usuario['primeiro_acesso'] === 'S') {
                    header('Location: trocar_senha.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $tentativas = (int) $usuario['tentativas_login'] + 1;

                if ($tentativas >= 3) {
                    $pdo->prepare("UPDATE Usuarios SET tentativas_login = ?, status = 'B' WHERE pk_username = ?")
                        ->execute([$tentativas, $username]);
                    registrarLog($pdo, $username, 'Usuário bloqueado após 3 tentativas de senha incorreta');
                    $erro = 'Senha incorreta. Seu usuário foi bloqueado após 3 tentativas.';
                } else {
                    $pdo->prepare("UPDATE Usuarios SET tentativas_login = ? WHERE pk_username = ?")
                        ->execute([$tentativas, $username]);
                    registrarLog($pdo, $username, "Tentativa de login incorreta ({$tentativas}/3)");
                    $restantes = 3 - $tentativas;
                    $erro = "Senha incorreta. Você tem mais {$restantes} tentativa(s) antes do bloqueio.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gerenciamento Mundial</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container fade-in" style="max-width: 420px; margin: 60px auto;">
        <h1>Login</h1>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label>Usuário<span class="required">*</span></label>
                <input type="text" name="username" required autofocus
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Senha<span class="required">*</span></label>
                <input type="password" name="senha" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>
