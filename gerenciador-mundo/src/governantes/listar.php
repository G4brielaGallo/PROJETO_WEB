<?php
include '../config.php';

$isAdmin = ($_SESSION['tipo'] ?? '') === 'A';

$governantes = $pdo->query("SELECT * FROM Governantes ORDER BY nome")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Governantes</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Governantes</h1>
        <div class="actions">
            <a href="../index.php" class="btn">Voltar</a>
            <?php if ($isAdmin): ?>
            <a href="criar.php" class="btn btn-success">+ Novo Governante</a>
            <?php endif; ?>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Partido</th>
                    <th>Nascimento</th>
                    <th>Idade</th>
                    <th>Início Mandato</th>
                    <th>Fim Mandato</th>
                    <?php if ($isAdmin): ?>
                    <th>Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(count($governantes) > 0): ?>
                    <?php foreach($governantes as $g): ?>
                    <tr>
                        <td><?= $g['pk_governante'] ?></td>
                        <td><strong><?= htmlspecialchars($g['nome']) ?></strong></td>
                        <td><?= htmlspecialchars($g['partido_politico']) ?></td>
                        <td><?= date('d/m/Y', strtotime($g['dt_nascimento'])) ?></td>
                        <td><?= $g['idade'] ?> anos</td>
                        <td><?= date('d/m/Y', strtotime($g['dt_inicio_mandato'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($g['dt_fim_mandato'])) ?></td>
                        <?php if ($isAdmin): ?>
                        <td>
                            <a href="editar.php?id=<?= $g['pk_governante'] ?>" class="btn-edit">✏️</a>
                            <a href="excluir.php?id=<?= $g['pk_governante'] ?>" class="btn-delete" onclick="return confirmarExclusao('governante')">🗑️</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= $isAdmin ? 8 : 7 ?>" style="text-align: center;">Nenhum governante cadastrado</td>
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