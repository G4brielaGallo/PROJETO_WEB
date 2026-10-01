<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análise Estatística de Turma</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        body { 
            font-family: sans-serif; 
            padding: 20px; 
            line-height: 1.6; 
            max-width: 600px; 
            margin: 0 auto; 
            background-color: #fcfcfc;
        }
    </style>
</head>
<body>
    <h1>Sistema de Análise Estatística</h1>

    <?php if (!isset($_POST['qtd_alunos'])): ?>
        <!-- Passo 1: Definir quantidade -->
        <form method="POST">
            <label>Nome da Turma:</label>
            <input type="text" name="nome_turma" required>
            
            <label>Quantidade de Alunos:</label>
            <input type="number" name="qtd_alunos" min="1" required>
            
            <br><br>
            <button type="submit">Gerar Campos</button>
        </form>

    <?php else: ?>
        <!-- Passo 2: Cadastro dos dados dos alunos -->
        <?php 
            $qtd = (int)$_POST['qtd_alunos'];
            $turma = htmlspecialchars($_POST['nome_turma']);
        ?>
        <h2>Turma: <?php echo $turma; ?></h2>
        <form action="processamento.php" method="POST">
            <input type="hidden" name="nome_turma" value="<?php echo $turma; ?>">
            <input type="hidden" name="qtd_alunos" value="<?php echo $qtd; ?>">

            <!--Cria os campos para inserção dos dados de cada aluno-->
            <?php for ($i = 0; $i < $qtd; $i++): ?>
                <div class="aluno-input">
                    <strong>Aluno <?php echo $i + 1; ?></strong>
                    <label>Nome:</label>
                    <input type="text" name="alunos[<?php echo $i; ?>][nome]" required>
                    
                    <label>Nota 1:</label>
                    <input type="number" name="alunos[<?php echo $i; ?>][n1]" step="0.1" min="0" max="10" required>
                    
                    <label>Nota 2:</label>
                    <input type="number" name="alunos[<?php echo $i; ?>][n2]" step="0.1" min="0" max="10" required>
                    
                    <label>Trabalho:</label>
                    <input type="number" name="alunos[<?php echo $i; ?>][t]" step="0.1" min="0" max="10" required>
                </div>
            <?php endfor; ?>

            <button type="submit">Processar Relatório</button>
            <a href="index.php">Voltar</a>
        </form>
    <?php endif; ?>
</body>
</html>