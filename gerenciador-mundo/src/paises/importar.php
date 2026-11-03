<?php
include '../auth.php';
exigirAdmin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar País da API</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container fade-in">
        <h1>🌐 Importar País da API</h1>

        <form action="importar_pais.php" method="POST">
            <div class="form-group">
                <label for="pais">Digite o nome do país (em inglês) *</label>
                <input type="text" id="pais" name="pais" placeholder="Exemplo: Argentina" maxlength="100" required>
                <span class="help-text">
                    A API REST Countries fornece os nomes em inglês (ex.: Brazil, Germany, Japan).
                    Países já cadastrados (mesmo código ISO) não são importados novamente.
                </span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">Consultar e Cadastrar</button>
                <a href="listar.php" class="btn">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
