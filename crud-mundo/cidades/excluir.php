<?php
include '../config.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM Cidades WHERE pk_cidade = ?");
        $stmt->execute([$id]);
        header('Location: listar.php?success=3');
        exit;
    } catch(PDOException $e) {
        $erro = "Erro ao excluir cidade: " . $e->getMessage();
    }
}

// Buscar dados da cidade para confirmação
$stmt = $pdo->prepare("SELECT nome FROM Cidades WHERE pk_cidade = ?");
$stmt->execute([$id]);
$cidade = $stmt->fetch();

if (!$cidade) {
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Cidade</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Excluir Cidade</h1>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <div class="confirm-box">
            <p>Tem certeza que deseja excluir a cidade <strong>"<?= htmlspecialchars($cidade['nome']) ?>"</strong>?</p>
            <p class="warning">⚠️ Esta ação não pode ser desfeita!</p>
            
            <form method="POST">
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Sim, excluir</button>
                    <a href="listar.php" class="btn">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>