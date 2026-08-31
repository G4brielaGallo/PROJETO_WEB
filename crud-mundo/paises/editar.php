<?php
include '../auth.php';
exigirAdmin();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE Paises SET nome=?, populacao=?, area=?, idioma=?, clima=?, regime_politico=?, moeda=?, fk_governante=?, fk_continente=? WHERE pk_pais=?");
    $stmt->execute([$_POST['nome'], $_POST['populacao'], $_POST['area'], $_POST['idioma'], 
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
            <input type="text" name="nome" value="<?= $pais['nome'] ?>" required>
            <input type="number" name="populacao" value="<?= $pais['populacao'] ?>" required>
            <input type="number" step="0.01" name="area" value="<?= $pais['area'] ?>" required>
            <input type="text" name="idioma" value="<?= $pais['idioma'] ?>" required>
            <input type="text" name="clima" value="<?= $pais['clima'] ?>" required>
            <input type="text" name="regime" value="<?= $pais['regime_politico'] ?>" required>
            <input type="text" name="moeda" value="<?= $pais['moeda'] ?>" required>
            
            <select name="governante" required>
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