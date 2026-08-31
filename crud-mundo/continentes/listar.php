<?php
include '../config.php';

$isAdmin = ($_SESSION['tipo'] ?? '') === 'A';

$continentes = $pdo->query("SELECT * FROM Continentes ORDER BY nome")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Continentes</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Continentes</h1>
        <div class="actions">
            <a href="../index.php" class="btn">Voltar</a>
            <?php if ($isAdmin): ?>
            <a href="criar.php" class="btn btn-success">+ Novo Continente</a>
            <?php endif; ?>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>População</th>
                    <th>Área (km²)</th>
                    <th>Total de Países</th>
                    <?php if ($isAdmin): ?>
                    <th>Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(count($continentes) > 0): ?>
                    <?php foreach($continentes as $c): ?>
                    <tr>
                        <td><?= $c['pk_continente'] ?></td>
                        <td><strong><?= htmlspecialchars($c['nome']) ?></strong></td>
                        <td><?= number_format($c['populacao'], 0, ',', '.') ?></td>
                        <td><?= number_format($c['area'], 2, ',', '.') ?></td>
                        <td><?= $c['total_paises'] ?></td>
                        <?php if ($isAdmin): ?>
                        <td>
                            <a href="editar.php?id=<?= $c['pk_continente'] ?>" class="btn-edit">✏️</a>
                            <a href="excluir.php?id=<?= $c['pk_continente'] ?>" class="btn-delete" onclick="return confirmarExclusao('continente')">🗑️</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= $isAdmin ? 6 : 5 ?>" style="text-align: center;">Nenhum continente cadastrado</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmarExclusao(tipo) {
            return confirm(`Tem certeza que deseja excluir este ${tipo}?`);
        }
    </script>
</body>
</html>