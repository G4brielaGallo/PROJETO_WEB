<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Estatístico - <?php echo isset($_POST['nome_turma']) ? $_POST['nome_turma'] : 'Turma'; ?></title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        body {
            font-family: sans-serif;
            padding: 15px;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fcfcfc;
        }
    </style>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['alunos'])) {
        $turma = $_POST['nome_turma'];
        $dados_alunos = $_POST['alunos'];
        $total_alunos = count($dados_alunos);

        // Variáveis para estatísticas da turma
        $soma_todas_notas = 0;
        $soma_medias_turma = 0;
        $maior_media = -1;
        $menor_media = 11;
        $cont_aprovados = 0;
        $cont_recuperacao = 0;
        $cont_reprovados = 0;
        $resultados_processados = [];

        foreach ($dados_alunos as $aluno) {
            $n1 = (float)$aluno['n1'];
            $n2 = (float)$aluno['n2'];
            $tr = (float)$aluno['t'];
            
            // Requisitos Funcionais
            $soma_aluno = $n1 + $n2 + $tr;
            $media = $soma_aluno / 3;
            $raiz_soma = sqrt($soma_aluno);
            $diff_abs = abs(max($n1, $n2, $tr) - min($n1, $n2, $tr));

            // Classificação
            if ($media >= 7.0) {
                $situacao = "Aprovado";
                $cont_aprovados++;
            } elseif ($media >= 5.0) {
                $situacao = "Recuperação";
                $cont_recuperacao++;
            } else {
                $situacao = "Reprovado";
                $cont_reprovados++;
            }

            // Acumuladores da Turma
            $soma_todas_notas += $soma_aluno;
            $soma_medias_turma += $media;
            if ($media > $maior_media) $maior_media = $media;
            if ($media < $menor_media) $menor_media = $media;

            // Armazena para o relatório
            $resultados_processados[] = [
                'nome' => htmlspecialchars($aluno['nome']),
                'media' => number_format($media, 2),
                'raiz' => number_format($raiz_soma, 2),
                'diff' => number_format($diff_abs, 2),
                'situacao' => $situacao
            ];
        }

        $media_geral_turma = $soma_medias_turma / $total_alunos;
        $perc_aprovacao = ($cont_aprovados / $total_alunos) * 100;

        // Exibição
        echo "<h1>Relatório Final</h1>";
        echo "<h2>Turma: $turma</h2>";
        
        echo "<h3>Resultados Individuais</h3>";
        // Div que ativa o scroll horizontal no mobile
        echo "<div class='table-responsive'>";
        echo "<table>
                <tr>
                    <th>Nome</th>
                    <th>Média</th>
                    <th>Raiz da Soma</th>
                    <th>Diff Absoluta</th>
                    <th>Situação</th>
                </tr>";
        foreach ($resultados_processados as $r) {
            $classe = strtolower(str_replace('çã', 'ca', $r['situacao']));
            echo "<tr>
                    <td>{$r['nome']}</td>
                    <td>{$r['media']}</td>
                    <td>{$r['raiz']}</td>
                    <td>{$r['diff']}</td>
                    <td class='$classe'>{$r['situacao']}</td>
                  </tr>";
        }
        echo "</table>";
        echo "</div>"; // Fim da div table-responsive

        echo "<div class='stats-box'>";
        echo "<h3>Estatísticas da Turma</h3>";
        echo "<p>Média Geral: <b>" . number_format($media_geral_turma, 2) . "</b></p>";
        echo "<p>Maior Média: <b>" . number_format($maior_media, 2) . "</b></p>";
        echo "<p>Menor Média: <b>" . number_format($menor_media, 2) . "</b></p>";
        echo "<p>Soma Total das Notas: <b>" . number_format($soma_todas_notas, 2) . "</b></p>";
        echo "<p>Percentual de Aprovação: <b>" . number_format($perc_aprovacao, 1) . "%</b></p>";
        echo "<hr>";
        echo "<ul>
                <li>Aprovados: $cont_aprovados</li>
                <li>Em Recuperação: $cont_recuperacao</li>
                <li>Reprovados: $cont_reprovados</li>
              </ul>";
        
        // Mensagem automática de desempenho
        if ($perc_aprovacao >= 70) {
            echo "<p style='color: green'><b>Desempenho Geral: Excelente! A turma atingiu a meta.</b></p>";
        } else {
            echo "<p style='color: red'><b>Desempenho Geral: Atenção necessária. Índice abaixo de 70%.</b></p>";
        }
        echo "</div>";
    } else {
        echo "<p>Nenhum dado foi enviado.</p>";
    }
    ?>
    <br>
    <a href="index.php" class="btn-voltar">Novo Lançamento</a>
</body>
</html>