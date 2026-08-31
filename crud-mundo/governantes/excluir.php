<?php
include '../auth.php';
exigirAdmin();

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Verificar se o governante está vinculado a algum país ou cidade
$checkPais = $pdo->prepare("SELECT COUNT(*) FROM Paises WHERE fk_governante = ?");
$checkPais->execute([$id]);
$totalPaises = $checkPais->fetchColumn();

$checkCidade = $pdo->prepare("SELECT COUNT(*) FROM Cidades WHERE fk_governante = ?");
$checkCidade->execute([$id]);
$totalCidades = $checkCidade->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if ($totalPaises > 0 || $totalCidades > 0) {
            $erro = "Não é possível excluir este governante pois está vinculado a ";
            if ($totalPaises > 0) $erro .= "$totalPaises país(es) ";
            if ($totalCidades > 0) $erro .= "e $totalCidades cidade(s)";
        } else {
            $stmt = $pdo->prepare("DELETE FROM Governantes WHERE pk_governante = ?");
            $stmt->execute([$id]);
            header('Location: listar.php?success=3');
            exit;
        }
    } catch(PDOException $e) {
        $erro = "Erro ao excluir governante: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT nome FROM Governantes WHERE pk_governante = ?");
$stmt->execute([$id]);
$governante = $stmt->fetch();

if (!$governante) {
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Governante</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Excluir Governante</h1>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <div class="confirm-box">
            <p>Tem certeza que deseja excluir o governante <strong>"<?= htmlspecialchars($governante['nome']) ?>"</strong>?</p>
            
            <?php if($totalPaises > 0 || $totalCidades > 0): ?>
                <div class="alert alert-warning">
                    ⚠️ Este governante está vinculado a:
                    <?php if($totalPaises > 0): ?>
                        <br>- <?= $totalPaises ?> país(es)
                    <?php endif; ?>
                    <?php if($totalCidades > 0): ?>
                        <br>- <?= $totalCidades ?> cidade(s)
                    <?php endif; ?>
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