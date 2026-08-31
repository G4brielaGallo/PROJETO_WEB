<?php
require_once 'auth.php';
exigirLogin();

$isAdmin = ($_SESSION['tipo'] ?? '') === 'A';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento - Países e Cidades</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container fade-in">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <h1 style="border-bottom:none; margin-bottom:0; padding-bottom:0;">🌍 Sistema de Gerenciamento Mundial</h1>
            <div style="font-size:14px; color:#7f8c8d;">
                Olá, <strong><?= htmlspecialchars($_SESSION['nome']) ?></strong>
                &nbsp;|&nbsp; <a href="logout.php">Sair</a>
            </div>
        </div>
        <div style="border-bottom: 3px solid #3498db; margin: 15px 0 25px;"></div>

        <?php
        // Buscar estatísticas
        try {
            $totalPaises = $pdo->query("SELECT COUNT(*) FROM Paises")->fetchColumn();
            $totalCidades = $pdo->query("SELECT COUNT(*) FROM Cidades")->fetchColumn();
            $totalContinentes = $pdo->query("SELECT COUNT(*) FROM Continentes")->fetchColumn();
            $totalGovernantes = $pdo->query("SELECT COUNT(*) FROM Governantes")->fetchColumn();
        } catch(PDOException $e) {
            $totalPaises = $totalCidades = $totalContinentes = $totalGovernantes = 0;
        }
        ?>

        <!-- Estatísticas do Dashboard -->
        <div class="stats">
            <div class="stat-card">
                <span class="number"><?= $totalPaises ?></span>
                <span class="label">Países</span>
            </div>
            <div class="stat-card">
                <span class="number"><?= $totalCidades ?></span>
                <span class="label">Cidades</span>
            </div>
            <div class="stat-card">
                <span class="number"><?= $totalContinentes ?></span>
                <span class="label">Continentes</span>
            </div>
            <div class="stat-card">
                <span class="number"><?= $totalGovernantes ?></span>
                <span class="label">Governantes</span>
            </div>
        </div>

        <div class="menu">
            <!-- PAÍSES -->
            <div class="menu-card">
                <h2>Países</h2>
                <a href="paises/listar.php" class="btn-list">
                    Listar Países
                    <span class="badge"><?= $totalPaises ?></span>
                </a>
                <?php if ($isAdmin): ?>
                <a href="paises/criar.php" class="btn-create">
                    Criar País
                </a>
                <?php endif; ?>
            </div>

            <!-- CIDADES -->
            <div class="menu-card">
                <h2>Cidades</h2>
                <a href="cidades/listar.php" class="btn-list">
                    Listar Cidades
                    <span class="badge"><?= $totalCidades ?></span>
                </a>
                <?php if ($isAdmin): ?>
                <a href="cidades/criar.php" class="btn-create">
                    Criar Cidade
                </a>
                <?php endif; ?>
            </div>

            <!-- CONTINENTES -->
            <div class="menu-card">
                <h2>Continentes</h2>
                <a href="continentes/listar.php" class="btn-list">
                    Listar Continentes
                    <span class="badge"><?= $totalContinentes ?></span>
                </a>
                <?php if ($isAdmin): ?>
                <a href="continentes/criar.php" class="btn-create">
                    Criar Continente
                </a>
                <?php endif; ?>
            </div>

            <!-- GOVERNANTES -->
            <div class="menu-card">
                <h2>Governantes</h2>
                <a href="governantes/listar.php" class="btn-list">
                    Listar Governantes
                    <span class="badge"><?= $totalGovernantes ?></span>
                </a>
                <?php if ($isAdmin): ?>
                <a href="governantes/criar.php" class="btn-create">
                    Criar Governante
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Rodapé -->
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px solid #ecf0f1; color: #7f8c8d; font-size: 14px;">
            <p>Sistema de Gerenciamento Mundial &copy; <?= date('Y') ?> - Todos os direitos reservados</p>
        </div>
    </div>
</body>
</html>