<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

/* =========================
   VALIDAÇÃO DE ACESSO
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['produto'])) {
    header("Location: produtos.php");
    exit;
}

/* =========================
   CARREGA PRODUTOS
========================= */
$produtosJson = file_get_contents('data/produtos.json');
$produtos = json_decode($produtosJson, true);

$produtoKey = $_POST['produto'];
if (!isset($produtos[$produtoKey])) {
    echo "Produto inválido!";
    exit;
}
$produto = $produtos[$produtoKey];

/* =========================
   MAPA DE NÍVEIS
========================= */
$mapaNivel1 = [
    "1" => "Nenhum",
    "2" => "Baixo",
    "3" => "Médio",
    "4" => "Alto"
];

$mapaNivel2 = [
    "1" => "Nenhum",
    "2" => "Ruim",
    "3" => "Mediano",
    "4" => "Bom"
];

$mapaNivel3 = [
    "1" => "Bruno",
    "2" => "Leonardo",
    "3" => "Matheus",
    "4" => "Robert",
    "5" => "Thiago"
];

/* =========================
   RESPOSTAS FIXAS DE SESSÃO
========================= */
$respostas = [
    'empresa'          => $_SESSION['empresa'] ?? '',
    'ordem_producao'   => $_SESSION['op'] ?? '',
    'data_inicio'      => $_SESSION['data_inicio'] ?? '',
    'data_conclusao'   => $_SESSION['data_fim'] ?? '',
    'data_previsao'    => $_SESSION['previsao'] ?? ''
];

/* =========================
   PROCESSA PERGUNTAS
========================= */
if (!empty($produto['blocos']) && is_array($produto['blocos'])) {
    foreach ($produto['blocos'] as $bloco) {

        // 1️⃣ Salva a pergunta de Aplicável
        $chaveAplicavel = $bloco['pergunta_aplicavel']['chave'] ?? null;
        $valorAplicavel = $_POST[$chaveAplicavel] ?? '';
        if ($chaveAplicavel) {
            $respostas[$chaveAplicavel] = $valorAplicavel;
        }

        // 2️⃣ Se o bloco não for aplicável, pula apenas as perguntas internas, mas mantém o aplicável
        if (strtolower($valorAplicavel) === 'nao') {
            continue;
        }

        // 3️⃣ Percorre as perguntas do bloco
        foreach ($bloco['perguntas'] as $pergunta) {
            $chave = $pergunta['chave'];
            $tipo  = $pergunta['tipo'] ?? 'texto';

            if ($tipo === 'min_max' || $tipo === 'rssi') {
                $min = $_POST[$chave . '_min'] ?? "";
                $max = $_POST[$chave . '_max'] ?? "";
                if ($min !== "" || $max !== "") {
                    $respostas[$chave] = ["min" => $min, "max" => $max];
                }
                continue;
            }

            $valor = $_POST[$chave] ?? "";

            // Converte número para valor textual nos níveis
            if ($tipo === 'nivel1' && isset($mapaNivel1[$valor])) {
                $valor = $mapaNivel1[$valor];
            }
            elseif ($tipo === 'nivel2' && isset($mapaNivel2[$valor])) {
                $valor = $mapaNivel2[$valor];
            }
            elseif ($tipo === 'nivel3' && isset($mapaNivel3[$valor])) {
                $valor = $mapaNivel3[$valor];
            }
            $respostas[$chave] = $valor;
        }
    }
}

/* =========================
   DATA DE GERAÇÃO
========================= */
$respostas['data_geracao'] = date('d/m/Y H:i:s');

/* =========================
   SALVA JSON
========================= */
$dir = "relatorios";
if (!is_dir($dir)) mkdir($dir, 0777, true);

$arquivo = $dir . "/relatorio_" . date("Ymd_His") . ".json";
file_put_contents($arquivo, json_encode($respostas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Final - <?= htmlspecialchars($produto['nome']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <h2>Relatório Final: <?= htmlspecialchars($produto['nome']) ?></h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Pergunta</th>
                <th>Resposta</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($respostas as $campo => $valor): ?>
            <tr>
                <td><?= htmlspecialchars(ucwords(str_replace("_", " ", $campo))) ?></td>
                <td>
                    <?php
                    if (is_array($valor)) {
                        echo "Min: " . htmlspecialchars($valor['min']) .
                             " | Max: " . htmlspecialchars($valor['max']);
                    } else {
                        echo htmlspecialchars($valor);
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="index.php">
        <button type="button" style="background-color:#28a745;">Novo Relatório</button>
    </a>
</div>

</body>
</html>
