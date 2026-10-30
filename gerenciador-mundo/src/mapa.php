<?php
require_once 'auth.php';
require_once __DIR__ . '/config/google_maps.php';
exigirLogin();

$chaveConfigurada = ($googleMapsKey !== '' && $googleMapsKey !== 'SUA_CHAVE_API');

// Se vier ?id=, mostra apenas aquela cidade; sem id, mostra todas as cidades com coordenadas.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$sql = "SELECT c.pk_cidade, c.nome, c.latitude, c.longitude, p.nome AS pais
        FROM Cidades c
        JOIN Paises p ON c.fk_pais = p.pk_pais
        WHERE c.latitude IS NOT NULL AND c.longitude IS NOT NULL";
$params = [];
if ($id) {
    $sql .= " AND c.pk_cidade = ?";
    $params[] = $id;
}
$sql .= " ORDER BY c.nome";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$marcadores = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
    $marcadores[] = [
        'id'   => (int)$linha['pk_cidade'],
        'nome' => $linha['nome'],
        'pais' => $linha['pais'],
        'lat'  => (float)$linha['latitude'],
        'lng'  => (float)$linha['longitude'],
    ];
}

// Lista para o seletor de cidades
$todas = $pdo->query("SELECT pk_cidade, nome FROM Cidades
                      WHERE latitude IS NOT NULL AND longitude IS NOT NULL
                      ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

$titulo = 'Mapa das Cidades';
if ($id && count($marcadores) === 1) {
    $titulo = 'Localização: ' . $marcadores[0]['nome'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Mundo - Google Maps</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container fade-in">
        <h1>🗺️ <?= htmlspecialchars($titulo) ?></h1>

        <div class="actions">
            <a href="cidades/listar.php" class="btn">Voltar às cidades</a>
            <a href="index.php" class="btn">Painel</a>
        </div>

        <?php if (!empty($todas)): ?>
        <div class="form-group" style="max-width:400px;">
            <label for="seletorCidade">Ir para uma cidade</label>
            <select id="seletorCidade">
                <option value="mapa.php">Todas as cidades</option>
                <?php foreach ($todas as $c): ?>
                    <option value="mapa.php?id=<?= (int)$c['pk_cidade'] ?>"
                        <?= ($id === (int)$c['pk_cidade']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if (!$chaveConfigurada): ?>
            <div class="alert alert-warning">
                ⚠️ A chave da API do Google Maps ainda não foi configurada.
                Defina a variável de ambiente <code>GOOGLE_MAPS_KEY</code> ou edite
                <code>src/config/google_maps.php</code>.
            </div>
        <?php elseif (empty($marcadores)): ?>
            <div class="alert alert-info">
                Nenhuma cidade com latitude/longitude cadastrada
                <?= $id ? 'para este registro' : '' ?>.
                Edite a cidade e informe as coordenadas.
            </div>
        <?php else: ?>
            <div id="mapa"></div>
        <?php endif; ?>
        <div id="erroMapa" class="alert alert-danger" style="display:none;"></div>
    </div>

    <script>
        const seletor = document.getElementById('seletorCidade');
        if (seletor) {
            seletor.addEventListener('change', function () {
                window.location.href = this.value;
            });
        }
    </script>

    <?php if ($chaveConfigurada && !empty($marcadores)): ?>
    <script>
        const marcadores = <?= json_encode($marcadores, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        function iniciarMapa() {
            const mapa = new google.maps.Map(document.getElementById('mapa'), {
                zoom: 12,
                center: { lat: marcadores[0].lat, lng: marcadores[0].lng }
            });
            const janela = new google.maps.InfoWindow();
            const limites = new google.maps.LatLngBounds();

            marcadores.forEach(function (m) {
                const posicao = { lat: m.lat, lng: m.lng };
                const marcador = new google.maps.Marker({
                    position: posicao,
                    map: mapa,
                    title: m.nome
                });
                limites.extend(posicao);

                marcador.addListener('click', function () {
                    // Monta o conteúdo com textContent para evitar injeção de HTML
                    const conteudo = document.createElement('div');
                    const nome = document.createElement('strong');
                    nome.textContent = m.nome;
                    conteudo.appendChild(nome);
                    conteudo.appendChild(document.createElement('br'));
                    conteudo.appendChild(document.createTextNode(m.pais));
                    janela.setContent(conteudo);
                    janela.open({ map: mapa, anchor: marcador });
                });
            });

            if (marcadores.length > 1) {
                mapa.fitBounds(limites);
            }
        }

        // Chamado pelo Google quando a chave é recusada
        function gm_authFailure() {
            const caixa = document.getElementById('erroMapa');
            caixa.textContent = 'O Google Maps recusou a chave da API. Verifique a chave, as restrições de domínio e se a Maps JavaScript API está habilitada.';
            caixa.style.display = 'block';
        }
        window.gm_authFailure = gm_authFailure;
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=<?= urlencode($googleMapsKey) ?>&callback=iniciarMapa&loading=async"
        async>
    </script>
    <?php endif; ?>
</body>
</html>
