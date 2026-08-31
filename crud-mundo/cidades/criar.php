<?php
include '../auth.php';
exigirAdmin();

// Buscar dados para os selects
$paises = $pdo->query("SELECT pk_pais, nome FROM Paises ORDER BY nome")->fetchAll();
$governantes = $pdo->query("SELECT pk_governante, nome FROM Governantes ORDER BY nome")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO Cidades (nome, populacao, area, clima, dt_fundacao, fk_governante, fk_pais) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $dt_fundacao = !empty($_POST['dt_fundacao']) ? $_POST['dt_fundacao'] : null;
        
        $stmt->execute([
            $_POST['nome'],
            $_POST['populacao'],
            $_POST['area'],
            $_POST['clima'],
            $dt_fundacao,
            $_POST['fk_governante'],
            $_POST['fk_pais']
        ]);
        
        header('Location: listar.php?success=1');
        exit;
    } catch(PDOException $e) {
        $erro = "Erro ao cadastrar cidade: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Cidade</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Nova Cidade</h1>
        
        <?php if(isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST" onsubmit="return validarFormulario()">
            <div class="form-group">
                <label for="nome">Nome da Cidade *</label>
                <input type="text" id="nome" name="nome" placeholder="Ex: São Paulo" required>
            </div>
            
            <div class="form-group">
                <label for="populacao">População *</label>
                <input type="number" id="populacao" name="populacao" placeholder="Ex: 11450000" required>
            </div>
            
            <div class="form-group">
                <label for="area">Área (km²) *</label>
                <input type="number" id="area" name="area" placeholder="Ex: 1521" required>
            </div>
            
            <div class="form-group">
                <label for="clima">Clima *</label>
                <input type="text" id="clima" name="clima" placeholder="Ex: Tropical" required>
            </div>
            
            <div class="form-group">
                <label for="dt_fundacao">Data de Fundação</label>
                <input type="date" id="dt_fundacao" name="dt_fundacao">
            </div>
            
            <div class="form-group">
                <label for="fk_governante">Governante *</label>
                <select id="fk_governante" name="fk_governante" required>
                    <option value="">Selecione um governante</option>
                    <?php foreach($governantes as $g): ?>
                        <option value="<?= $g['pk_governante'] ?>"><?= htmlspecialchars($g['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="fk_pais">País *</label>
                <select id="fk_pais" name="fk_pais" required>
                    <option value="">Selecione um país</option>
                    <?php foreach($paises as $p): ?>
                        <option value="<?= $p['pk_pais'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Salvar</button>
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