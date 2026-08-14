<?php
include '../config.php';

$stmt = $pdo->query("SELECT c.*, p.nome as pais, g.nome as governante 
                     FROM Cidades c 
                     JOIN Paises p ON c.fk_pais = p.pk_pais 
                     JOIN Governantes g ON c.fk_governante = g.pk_governante
                     ORDER BY c.nome");
$cidades = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cidades</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Cidades</h1>
        <div class="actions">
            <a href="../index.php" class="btn">Voltar</a>
            <a href="criar.php" class="btn btn-success">+ Nova Cidade</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>População</th>
                    <th>Área (km²)</th>
                    <th>Clima</th>
                    <th>Fundação</th>
                    <th>País</th>
                    <th>Governante</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($cidades) > 0): ?>
                    <?php foreach($cidades as $c): ?>
                    <tr>
                        <td><?= $c['pk_cidade'] ?></td>
                        <td><?= htmlspecialchars($c['nome']) ?></td>
                        <td><?= number_format($c['populacao'], 0, ',', '.') ?></td>
                        <td><?= number_format($c['area'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($c['clima']) ?></td>
                        <td><?= $c['dt_fundacao'] ? date('d/m/Y', strtotime($c['dt_fundacao'])) : '-' ?></td>
                        <td><?= htmlspecialchars($c['pais']) ?></td>
                        <td><?= htmlspecialchars($c['governante']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $c['pk_cidade'] ?>" class="btn-edit">✏️</a>
                            <a href="excluir.php?id=<?= $c['pk_cidade'] ?>" class="btn-delete" onclick="return confirmarExclusao('cidade')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Nenhuma cidade cadastrada</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmarExclusao(tipo) {
            return confirm(`Tem certeza que deseja excluir esta ${tipo}?`);
        }
    </script>
</body>
</html>