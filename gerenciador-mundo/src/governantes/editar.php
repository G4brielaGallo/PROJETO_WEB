<?php
include '../auth.php';
exigirAdmin();

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM Governantes WHERE pk_governante = ?");
$stmt->execute([$id]);
$governante = $stmt->fetch();

if (!$governante) {
    header('Location: listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Calcular idade
        $dt_nascimento = new DateTime($_POST['dt_nascimento']);
        $hoje = new DateTime();
        $idade = $hoje->diff($dt_nascimento)->y;
        
        $stmt = $pdo->prepare("UPDATE Governantes SET 
                               nome = ?, 
                               partido_politico = ?, 
                               dt_nascimento = ?, 
                               idade = ?, 
                               dt_inicio_mandato = ?, 
                               dt_fim_mandato = ? 
                               WHERE pk_governante = ?");
        
        $stmt->execute([
            $_POST['nome'],
            $_POST['partido_politico'],
            $_POST['dt_nascimento'],
            $idade,
            $_POST['dt_inicio_mandato'],
            $_POST['dt_fim_mandato'],
            $id
        ]);
        
        header('Location: listar.php?success=2');
        exit;
    } catch(PDOException $e) {
        $erro = "Erro ao atualizar governante: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Governante</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Governante</h1>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST" onsubmit="return validarFormulario()">
            <div class="form-group">
                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($governante['nome']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="partido_politico">Partido Político *</label>
                <input type="text" id="partido_politico" name="partido_politico" value="<?= htmlspecialchars($governante['partido_politico']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="dt_nascimento">Data de Nascimento *</label>
                <input type="date" id="dt_nascimento" name="dt_nascimento" value="<?= $governante['dt_nascimento'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="dt_inicio_mandato">Data de Início do Mandato *</label>
                <input type="date" id="dt_inicio_mandato" name="dt_inicio_mandato" value="<?= $governante['dt_inicio_mandato'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="dt_fim_mandato">Data de Fim do Mandato *</label>
                <input type="date" id="dt_fim_mandato" name="dt_fim_mandato" value="<?= $governante['dt_fim_mandato'] ?>" required>
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
            
            let dt_inicio = document.getElementById('dt_inicio_mandato').value;
            let dt_fim = document.getElementById('dt_fim_mandato').value;
            
            if(dt_inicio && dt_fim && dt_inicio > dt_fim) {
                alert('A data de início do mandato não pode ser posterior à data de fim!');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>