<?php
include '../auth.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Verificar se o nome do continente já existe
        $check = $pdo->prepare("SELECT COUNT(*) FROM Continentes WHERE nome = ?");
        $check->execute([$_POST['nome']]);
        if ($check->fetchColumn() > 0) {
            $erro = "Já existe um continente com este nome!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO Continentes (nome, populacao, area, total_paises) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_POST['nome'],
                $_POST['populacao'],
                $_POST['area'],
                $_POST['total_paises']
            ]);
            header('Location: listar.php?success=1');
            exit;
        }
    } catch(PDOException $e) {
        $erro = "Erro ao cadastrar continente: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Continente</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Novo Continente</h1>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST" onsubmit="return validarFormulario()">
            <div class="form-group">
                <label for="nome">Nome do Continente *</label>
                <input type="text" id="nome" name="nome" placeholder="Ex: Ásia" required>
            </div>
            
            <div class="form-group">
                <label for="populacao">População *</label>
                <input type="number" id="populacao" name="populacao" placeholder="Ex: 4700000000" required>
            </div>
            
            <div class="form-group">
                <label for="area">Área (km²) *</label>
                <input type="number" step="0.01" id="area" name="area" placeholder="Ex: 44000000" required>
            </div>
            
            <div class="form-group">
                <label for="total_paises">Total de Países *</label>
                <input type="number" id="total_paises" name="total_paises" placeholder="Ex: 54" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Salvar</button>
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