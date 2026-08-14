<?php
include '../config.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Buscar dados da cidade
$stmt = $pdo->prepare("SELECT * FROM Cidades WHERE pk_cidade = ?");
$stmt->execute([$id]);
$cidade = $stmt->fetch();

if (!$cidade) {
    header('Location: listar.php');
    exit;
}

// Buscar dados para os selects
$paises = $pdo->query("SELECT pk_pais, nome FROM Paises ORDER BY nome")->fetchAll();
$governantes = $pdo->query("SELECT pk_governante, nome FROM Governantes ORDER BY nome")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("UPDATE Cidades SET 
                               nome = ?, 
                               populacao = ?, 
                               area = ?, 
                               clima = ?, 
                               dt_fundacao = ?, 
                               fk_governante = ?, 
                               fk_pais = ? 
                               WHERE pk_cidade = ?");
        
        $dt_fundacao = !empty($_POST['dt_fundacao']) ? $_POST['dt_fundacao'] : null;
        
        $stmt->execute([
            $_POST['nome'],
            $_POST['populacao'],
            $_POST['area'],
            $_POST['clima'],
            $dt_fundacao,
            $_POST['fk_governante'],
            $_POST['fk_pais'],
            $id
        ]);
        
        header('Location: listar.php?success=2');
        exit;
    } catch(PDOException $e) {
        $erro = "Erro ao atualizar cidade: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cidade</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Cidade</h1>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST" onsubmit="return validarFormulario()">
            <div class="form-group">
                <label for="nome">Nome da Cidade *</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cidade['nome']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="populacao">População *</label>
                <input type="number" id="populacao" name="populacao" value="<?= $cidade['populacao'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="area">Área (km²) *</label>
                <input type="number" id="area" name="area" value="<?= $cidade['area'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="clima">Clima *</label>
                <input type="text" id="clima" name="clima" value="<?= htmlspecialchars($cidade['clima']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="dt_fundacao">Data de Fundação</label>
                <input type="date" id="dt_fundacao" name="dt_fundacao" value="<?= $cidade['dt_fundacao'] ?>">
            </div>
            
            <div class="form-group">
                <label for="fk_governante">Governante *</label>
                <select id="fk_governante" name="fk_governante" required>
                    <option value="">Selecione um governante</option>
                    <?php foreach($governantes as $g): ?>
                        <option value="<?= $g['pk_governante'] ?>" <?= $g['pk_governante'] == $cidade['fk_governante'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="fk_pais">País *</label>
                <select id="fk_pais" name="fk_pais" required>
                    <option value="">Selecione um país</option>
                    <?php foreach($paises as $p): ?>
                        <option value="<?= $p['pk_pais'] ?>" <?= $p['pk_pais'] == $cidade['fk_pais'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Atualizar</button>
                <a href="listar.php" class="btn">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        function validarFormulario() {
            let inputs = document.querySelectorAll('input[required], select[required]');
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