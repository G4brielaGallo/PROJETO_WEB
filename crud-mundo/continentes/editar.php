<?php
include '../config.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM Continentes WHERE pk_continente = ?");
$stmt->execute([$id]);
$continente = $stmt->fetch();

if (!$continente) {
    header('Location: listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Verificar se o nome já existe (exceto o próprio registro)
        $check = $pdo->prepare("SELECT COUNT(*) FROM Continentes WHERE nome = ? AND pk_continente != ?");
        $check->execute([$_POST['nome'], $id]);
        if ($check->fetchColumn() > 0) {
            $erro = "Já existe um continente com este nome!";
        } else {
            $stmt = $pdo->prepare("UPDATE Continentes SET nome = ?, populacao = ?, area = ?, total_paises = ? WHERE pk_continente = ?");
            $stmt->execute([
                $_POST['nome'],
                $_POST['populacao'],
                $_POST['area'],
                $_POST['total_paises'],
                $id
            ]);
            header('Location: listar.php?success=2');
            exit;
        }
    } catch(PDOException $e) {
        $erro = "Erro ao atualizar continente: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Continente</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Continente</h1>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST" onsubmit="return validarFormulario()">
            <div class="form-group">
                <label for="nome">Nome do Continente *</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($continente['nome']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="populacao">População *</label>
                <input type="number" id="populacao" name="populacao" value="<?= $continente['populacao'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="area">Área (km²) *</label>
                <input type="number" step="0.01" id="area" name="area" value="<?= $continente['area'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="total_paises">Total de Países *</label>
                <input type="number" id="total_paises" name="total_paises" value="<?= $continente['total_paises'] ?>" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Atualizar</button>
                <a href="listar.php" class="btn">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        function validarFormulario() {
            let inputs = document.querySelectorAll('input[required]');
            for(let input of inputs) {
                if(!input.value.trim()) {
                    alert('Preencha todos os campos obrigatórios!');
                    input.focus();
                    return false;
                }
            }
            return true;
        }
    </script>
</body>
</html>