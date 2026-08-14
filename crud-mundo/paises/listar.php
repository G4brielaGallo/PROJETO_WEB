<?php
include '../config.php';

// Buscar todos os países com seus continentes e governantes
$stmt = $pdo->query("SELECT p.*, c.nome as continente, g.nome as governante 
                     FROM Paises p 
                     JOIN Continentes c ON p.fk_continente = c.pk_continente 
                     JOIN Governantes g ON p.fk_governante = g.pk_governante
                     ORDER BY p.nome");
$paises = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Países</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container fade-in">
        <h1>Lista de Países</h1>
        
        <div class="actions">
            <a href="../index.php" class="btn">Voltar</a>
            <a href="criar.php" class="btn btn-success">+ Novo País</a>
        </div>
        
        <!-- Estatísticas rápidas -->
        <div class="stats">
            <div class="stat-card">
                <span class="number"><?= count($paises) ?></span>
                <span class="label">Total de Países</span>
            </div>
            <div class="stat-card">
                <span class="number">
                    <?php 
                    $totalPop = array_sum(array_column($paises, 'populacao'));
                    echo number_format($totalPop, 0, ',', '.');
                    ?>
                </span>
                <span class="label">População Total</span>
            </div>
        </div>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php 
                switch($_GET['success']) {
                    case 1: echo "✅ País cadastrado com sucesso!"; break;
                    case 2: echo "✅ País atualizado com sucesso!"; break;
                    case 3: echo "✅ País excluído com sucesso!"; break;
                }
                ?>
            </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>População</th>
                    <th>Área (km²)</th>
                    <th>Idioma</th>
                    <th>Clima</th>
                    <th>Regime</th>
                    <th>Moeda</th>
                    <th>Continente</th>
                    <th>Governante</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($paises) > 0): ?>
                    <?php foreach($paises as $p): ?>
                    <tr>
                        <td><?= $p['pk_pais'] ?></td>
                        <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                        <td><?= number_format($p['populacao'], 0, ',', '.') ?></td>
                        <td><?= number_format($p['area'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($p['idioma']) ?></td>
                        <td><?= htmlspecialchars($p['clima']) ?></td>
                        <td><?= htmlspecialchars($p['regime_politico']) ?></td>
                        <td><?= htmlspecialchars($p['moeda']) ?></td>
                        <td><?= htmlspecialchars($p['continente']) ?></td>
                        <td><?= htmlspecialchars($p['governante']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $p['pk_pais'] ?>" class="btn-edit" title="Editar">✏️</a>
                            <a href="excluir.php?id=<?= $p['pk_pais'] ?>" class="btn-delete" title="Excluir" onclick="return confirmarExclusao('país')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" style="text-align: center; padding: 30px; color: #7f8c8d;">
                            Nenhum país cadastrado
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmarExclusao(tipo) {
            return confirm(`Tem certeza que deseja excluir este ${tipo}?`);
        }

        // Auto-fechar alertas após 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>