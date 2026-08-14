<?php
include '../config.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Verificar se existem países vinculados
$check = $pdo->prepare("SELECT COUNT(*) FROM Paises WHERE fk_continente = ?");
$check->execute([$id]);
$totalPaises = $check->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if ($totalPaises > 0) {
            $erro = "Não é possível excluir este continente pois existem $totalPaises países vinculados a ele!";
        } else {
            $stmt = $pdo->prepare("DELETE FROM Continentes WHERE pk_continente = ?");
            $stmt->execute([$id]);
            header('Location: listar.php?success=3');
            exit;
        }
    } catch(PDOException $e) {
        $erro = "Erro ao excluir continente: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT nome FROM Continentes WHERE pk_continente = ?");
$stmt->execute([$id]);
$continente = $stmt->fetch();

if (!$continente) {
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Continente</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Excluir Continente</h1>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <div class="confirm-box">
            <p>Tem certeza que deseja excluir o continente <strong>"<?= htmlspecialchars($continente['nome']) ?>"</strong>?</p>
            
            <?php if($totalPaises > 0): ?>
                <div class="alert alert-warning">
                    ⚠️ Este continente possui <strong><?= $totalPaises ?></strong> países vinculados. 
                    Não é possível excluí-lo.
                </div>
                <div class="form-actions">
                    <a href="listar.php" class="btn">Voltar</a>
                </div>
            <?php else: ?>
                <p class="warning">⚠️ Esta ação não pode ser desfeita!</p>
                <form method="POST">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger">Sim, excluir</button>
                        <a href="listar.php" class="btn">Cancelar</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>