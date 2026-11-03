<?php
include '../auth.php';
require_once __DIR__ . '/../config/api.php';
exigirAdmin();

// Dois processos distintos:
//   1) GET na API REST Countries (consulta; não altera nada na API)
//   2) INSERT no MySQL local com PDO

// Tradução da região da API para os continentes cadastrados no banco
const MAPA_REGIOES = [
    'Americas'  => 'América',
    'Asia'      => 'Ásia',
    'Africa'    => 'África',
    'Europe'    => 'Europa',
    'Oceania'   => 'Oceania',
    'Antarctic' => 'Antártida',
];

$sucesso = false;
$mensagem = '';
$idCadastrado = null;
$nome = $codigo = '';

try {
    // 1. Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Acesso inválido.');
    }

    // 2. Receber e validar o nome do país
    $nomeBusca = trim($_POST['pais'] ?? '');
    if ($nomeBusca === '') {
        throw new Exception('Informe o nome de um país.');
    }
    if (mb_strlen($nomeBusca) > 100) {
        throw new Exception('Nome do país muito longo.');
    }
    if ($apiKey === '') {
        throw new Exception('Chave da API não configurada.');
    }
    if (!function_exists('curl_init')) {
        throw new Exception('A extensão cURL do PHP não está habilitada.');
    }

    // 3. Montar o endereço da API
    $url = 'https://api.restcountries.com'
         . '/countries/v5/names.common/'
         . rawurlencode($nomeBusca);

    // 4. Inicializar o cURL
    $curl = curl_init($url);
    if ($curl === false) {
        throw new Exception('Falha ao inicializar cURL.');
    }

    // 5. Configurar a requisição GET
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPGET        => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $apiKey,
            'Accept: application/json',
        ],
    ]);

    // 6. Executar a requisição
    $resposta   = curl_exec($curl);
    $statusHTTP = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $erroCurl   = curl_error($curl);
    curl_close($curl);

    // 7. Verificar erros de comunicação
    if ($resposta === false) {
        throw new Exception('Falha na comunicação: ' . $erroCurl);
    }
    if ($statusHTTP === 404) {
        throw new Exception('País não encontrado na API.');
    }
    if ($statusHTTP !== 200) {
        throw new Exception('Erro na API. HTTP: ' . $statusHTTP);
    }

    // 8. Converter JSON em array PHP
    $dados = json_decode($resposta, true, 512, JSON_THROW_ON_ERROR);

    // 9. Obter o primeiro país retornado
    $pais = $dados['data']['objects'][0] ?? null;
    if (!is_array($pais)) {
        throw new Exception('A API não retornou dados válidos.');
    }

    // 10. Extrair as informações (JSON com objetos e arrays aninhados)
    $nome      = $pais['names']['common']      ?? null;
    $codigo    = $pais['codes']['alpha_2']     ?? null;
    $capital   = $pais['capitals'][0]['name']  ?? null;   // somente a primeira capital
    $populacao = $pais['population']           ?? null;
    $regiao    = $pais['region']               ?? null;
    $subRegiao = $pais['subregion']            ?? null;

    // 11. Validar campos obrigatórios
    if (!is_string($nome) || trim($nome) === '' || mb_strlen($nome) > 90
        || !is_string($codigo) || !preg_match('/^[A-Z]{2}$/', $codigo)) {
        throw new Exception('Dados obrigatórios inválidos na API.');
    }

    // Validar campos opcionais
    if ($capital !== null && !is_string($capital))     { $capital = null; }
    if ($regiao !== null && !is_string($regiao))       { $regiao = null; }
    if ($subRegiao !== null && !is_string($subRegiao)) { $subRegiao = null; }

    foreach ([$capital, $subRegiao] as $valor) {
        if ($valor !== null && mb_strlen($valor) > 100) {
            throw new Exception('Um dos campos excede o tamanho permitido.');
        }
    }
    if ($populacao !== null && (!is_int($populacao) || $populacao < 0)) {
        throw new Exception('População inválida.');
    }
    // Paises.populacao é NOT NULL neste projeto
    if ($populacao === null) {
        throw new Exception('A API não informou a população do país.');
    }

    // 12. Descobrir o continente local a partir da região da API
    if ($regiao === null || !isset(MAPA_REGIOES[$regiao])) {
        throw new Exception('Região da API sem continente correspondente no banco.');
    }
    $stmt = $pdo->prepare('SELECT pk_continente FROM Continentes WHERE nome = ?');
    $stmt->execute([MAPA_REGIOES[$regiao]]);
    $idContinente = $stmt->fetchColumn();
    if ($idContinente === false) {
        throw new Exception('Continente "' . MAPA_REGIOES[$regiao] . '" não cadastrado no banco.');
    }

    // 13. Verificar se o país já existe (pelo código ISO)
    $stmt = $pdo->prepare('SELECT pk_pais FROM Paises WHERE codigo_iso = :codigo');
    $stmt->execute([':codigo' => $codigo]);
    if ($stmt->fetch()) {
        throw new Exception('Este país já está cadastrado.');
    }

    // 14. Preparar a instrução INSERT (consulta parametrizada)
    $sql = 'INSERT INTO Paises (nome, codigo_iso, capital, sub_regiao, populacao, fk_continente)
            VALUES (:nome, :codigo, :capital, :sub_regiao, :populacao, :continente)';
    $stmt = $pdo->prepare($sql);

    // 15. Associar os parâmetros
    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':codigo', $codigo, PDO::PARAM_STR);
    $stmt->bindValue(':capital', $capital, $capital === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':sub_regiao', $subRegiao, $subRegiao === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':populacao', $populacao, PDO::PARAM_INT);
    $stmt->bindValue(':continente', (int)$idContinente, PDO::PARAM_INT);

    // 16. Executar o INSERT
    $stmt->execute();

    // 17. Recuperar o ID gerado
    $idCadastrado = $pdo->lastInsertId();
    $mensagem = 'País cadastrado com sucesso!';
    $sucesso = true;

} catch (PDOException $erro) {
    error_log($erro->getMessage());
    // 23000 = violação de UNIQUE (ex.: mesmo nome já cadastrado com outro código)
    $mensagem = ($erro->getCode() === '23000')
        ? 'Já existe um país com este nome ou código ISO no banco.'
        : 'Erro ao gravar no banco de dados.';
    $sucesso = false;
} catch (Throwable $erro) {
    $mensagem = $erro->getMessage();
    $sucesso = false;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Importação</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container fade-in">
        <h1>Resultado da importação</h1>

        <div class="alert <?= $sucesso ? 'alert-success' : 'alert-danger' ?>">
            <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
        </div>

        <?php if ($sucesso): ?>
            <p>ID cadastrado: <strong><?= htmlspecialchars((string)$idCadastrado) ?></strong></p>
            <p>País: <strong><?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?></strong></p>
            <p>Código ISO: <strong><?= htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8') ?></strong></p>
            <p style="color:#7f8c8d; font-size:14px;">
                Área, idioma, clima, regime, moeda e governante não são fornecidos pela API;
                complete-os em <em>Editar</em> se desejar.
            </p>
        <?php endif; ?>

        <div class="actions">
            <a href="importar.php" class="btn btn-success">Nova consulta</a>
            <a href="listar.php" class="btn">Ver países cadastrados</a>
        </div>
    </div>
</body>
</html>
