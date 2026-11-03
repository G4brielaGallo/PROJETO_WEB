<?php
include '../auth.php';
exigirAdmin();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Campos opcionais: string vazia vira NULL (o código ISO é UNIQUE)
    $iso        = strtoupper(trim($_POST['codigo_iso'] ?? ''));
    $iso        = $iso !== '' ? $iso : null;
    $capital    = trim($_POST['capital'] ?? '');
    $capital    = $capital !== '' ? $capital : null;
    $subRegiao  = trim($_POST['sub_regiao'] ?? '');
    $subRegiao  = $subRegiao !== '' ? $subRegiao : null;

    $stmt = $pdo->prepare("UPDATE Paises SET nome=?, codigo_iso=?, capital=?, sub_regiao=?, populacao=?, area=?, idioma=?, clima=?, regime_politico=?, moeda=?, fk_governante=?, fk_continente=? WHERE pk_pais=?");
    $stmt->execute([$_POST['nome'], $iso, $capital, $subRegiao, $_POST['populacao'], $_POST['area'], $_POST['idioma'], 
                    $_POST['clima'], $_POST['regime'], $_POST['moeda'], $_POST['governante'], $_POST['continente'], $id]);
    header('Location: listar.php');
    exit;
}

$pais = $pdo->prepare("SELECT * FROM Paises WHERE pk_pais = ?");
$pais->execute([$id]);
$pais = $pais->fetch();

$governantes = $pdo->query("SELECT pk_governante, nome FROM Governantes")->fetchAll();
$continentes = $pdo->query("SELECT pk_continente, nome FROM Continentes")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar País</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Editar País</h1>
        <form method="POST">
            <input type="text" name="nome" value="<?= htmlspecialchars($pais['nome']) ?>" required>
            <input type="text" name="codigo_iso" value="<?= htmlspecialchars($pais['codigo_iso'] ?? '') ?>" placeholder="Código ISO (2 letras, opcional)" maxlength="2" pattern="[A-Za-z]{2}">
            <input type="text" name="capital" value="<?= htmlspecialchars($pais['capital'] ?? '') ?>" placeholder="Capital (opcional)" maxlength="100">
            <input type="text" name="sub_regiao" value="<?= htmlspecialchars($pais['sub_regiao'] ?? '') ?>" placeholder="Sub-região (opcional)" maxlength="100">
            <input type="number" name="populacao" value="<?= htmlspecialchars((string)$pais['populacao']) ?>" required>
            <input type="number" step="0.01" name="area" value="<?= htmlspecialchars((string)($pais['area'] ?? '')) ?>" placeholder="Área (km²)" required>
            <input type="text" name="idioma" value="<?= htmlspecialchars($pais['idioma'] ?? '') ?>" placeholder="Idioma" required>
            <input type="text" name="clima" value="<?= htmlspecialchars($pais['clima'] ?? '') ?>" placeholder="Clima" required>
            <input type="text" name="regime" value="<?= htmlspecialchars($pais['regime_politico'] ?? '') ?>" placeholder="Regime Político" required>
            <input type="text" name="moeda" value="<?= htmlspecialchars($pais['moeda'] ?? '') ?>" placeholder="Moeda" required>
            
            <select name="governante" required>
                <option value="">Selecione o Governante</option>
                <?php foreach($governantes as $g): ?>
                    <option value="<?= $g['pk_governante'] ?>" <?= $g['pk_governante'] == $pais['fk_governante'] ? 'selected' : '' ?>>
                        <?= $g['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <select name="continente" required>
                <?php foreach($continentes as $c): ?>
                    <option value="<?= $c['pk_continente'] ?>" <?= $c['pk_continente'] == $pais['fk_continente'] ? 'selected' : '' ?>>
                        <?= $c['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Atualizar</button>
            <a href="listar.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>