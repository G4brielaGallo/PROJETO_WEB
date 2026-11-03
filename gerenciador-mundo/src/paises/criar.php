<?php
include '../auth.php';
exigirAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Campos opcionais: string vazia vira NULL (o código ISO é UNIQUE)
    $iso        = strtoupper(trim($_POST['codigo_iso'] ?? ''));
    $iso        = $iso !== '' ? $iso : null;
    $capital    = trim($_POST['capital'] ?? '');
    $capital    = $capital !== '' ? $capital : null;
    $subRegiao  = trim($_POST['sub_regiao'] ?? '');
    $subRegiao  = $subRegiao !== '' ? $subRegiao : null;

    $stmt = $pdo->prepare("INSERT INTO Paises (nome, codigo_iso, capital, sub_regiao, populacao, area, idioma, clima, regime_politico, moeda, fk_governante, fk_continente) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['nome'], $iso, $capital, $subRegiao, $_POST['populacao'], $_POST['area'], $_POST['idioma'], 
                    $_POST['clima'], $_POST['regime'], $_POST['moeda'], $_POST['governante'], $_POST['continente']]);
    header('Location: listar.php');
    exit;
}

$governantes = $pdo->query("SELECT pk_governante, nome FROM Governantes")->fetchAll();
$continentes = $pdo->query("SELECT pk_continente, nome FROM Continentes")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Criar País</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Novo País</h1>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="text" name="codigo_iso" placeholder="Código ISO (2 letras, opcional) Ex: BR" maxlength="2" pattern="[A-Za-z]{2}">
            <input type="text" name="capital" placeholder="Capital (opcional)" maxlength="100">
            <input type="text" name="sub_regiao" placeholder="Sub-região (opcional)" maxlength="100">
            <input type="number" name="populacao" placeholder="População" required>
            <input type="number" step="0.01" name="area" placeholder="Área (km²)" required>
            <input type="text" name="idioma" placeholder="Idioma" required>
            <input type="text" name="clima" placeholder="Clima" required>
            <input type="text" name="regime" placeholder="Regime Político" required>
            <input type="text" name="moeda" placeholder="Moeda" required>
            
            <select name="governante" required>
                <option value="">Selecione o Governante</option>
                <?php foreach($governantes as $g): ?>
                    <option value="<?= $g['pk_governante'] ?>"><?= $g['nome'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="continente" required>
                <option value="">Selecione o Continente</option>
                <?php foreach($continentes as $c): ?>
                    <option value="<?= $c['pk_continente'] ?>"><?= $c['nome'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Salvar</button>
            <a href="listar.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>