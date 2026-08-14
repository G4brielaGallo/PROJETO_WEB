<?php
include '../config.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Verificar se existem cidades vinculadas ao país
$checkCidades = $pdo->prepare("SELECT COUNT(*) FROM Cidades WHERE fk_pais = ?");
$checkCidades->execute([$id]);
$totalCidades = $checkCidades->fetchColumn();

// Buscar nome do país para exibição
$stmt = $pdo->prepare("SELECT nome FROM Paises WHERE pk_pais = ?");
$stmt->execute([$id]);
$pais = $stmt->fetch();

if (!$pais) {
    header('Location: listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Verificar novamente se há cidades (por segurança)
        if ($totalCidades > 0) {
            $erro = "Não é possível excluir este país pois existem $totalCidades cidade(s) vinculadas a ele!";
        } else {
            $stmt = $pdo->prepare("DELETE FROM Paises WHERE pk_pais = ?");
            $stmt->execute([$id]);
            header('Location: listar.php?success=3');
            exit;
        }
    } catch(PDOException $e) {
        $erro = "Erro ao excluir país: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir País</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>🗑️ Excluir País</h1>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <div class="confirm-box">
            <p>Tem certeza que deseja excluir o país <strong>"<?= htmlspecialchars($pais['nome']) ?>"</strong>?</p>
            
            <?php if($totalCidades > 0): ?>
                <div class="alert alert-warning">
                    ⚠️ Este país possui <strong><?= $totalCidades ?></strong> cidade(s) vinculada(s). 
                    <br><strong>Não é possível excluí-lo.</strong>
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