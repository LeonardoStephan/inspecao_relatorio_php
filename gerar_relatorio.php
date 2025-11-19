<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['produto'])) {
    header("Location: produtos.php");
    exit;
}

// --------------------------------------------------------------------
//  RECONSTROI DADOS DAS INFORMAÇÕES INICIAIS
// --------------------------------------------------------------------

$anoFixo = "2025";

// Garante que a OP sempre tenha o formato 2025/XXXXX
if (isset($_POST['op'])) {
    $_SESSION['op'] = $anoFixo . "/" . preg_replace('/\D/', '', $_POST['op']);
}

$respostas = [];
$respostas['empresa']         = $_SESSION['empresa'] ?? '';
$respostas['ordem_producao']  = $_SESSION['op'] ?? '';
$respostas['data_inicio']     = $_SESSION['data_inicio'] ?? '';
$respostas['data_conclusao']  = $_SESSION['data_fim'] ?? '';
$respostas['data_previsao']   = $_SESSION['previsao'] ?? '';


// --------------------------------------------------------------------
//  CARREGA JSON DO PRODUTO
// --------------------------------------------------------------------
$produtoKey = $_POST['produto'];
$produtosJson = file_get_contents('data/produtos.json');
$produtos = json_decode($produtosJson, true);

if (!isset($produtos[$produtoKey])) {
    echo "Produto inválido!";
    exit;
}

$produto = $produtos[$produtoKey];


// --------------------------------------------------------------------
//  PROCESSA PERGUNTAS
// --------------------------------------------------------------------
$skipBloco = false;

foreach ($produto['perguntas'] as $pergunta) {

    $chave = $pergunta['chave'];
    $tipo  = $pergunta['tipo'];

    // MIN/MAX ou RSSI
    if (in_array($tipo, ['min_max','rssi'])) {

        $min = $_POST[$chave . "_min"] ?? "";
        $max = $_POST[$chave . "_max"] ?? "";

        if ($min !== "" && $max !== "" && $max < $min) {
            echo "<script>alert('Erro: o valor máximo de $chave não pode ser menor que o mínimo.'); history.back();</script>";
            exit;
        }

        if ($min !== "" || $max !== "") {
            $respostas[$chave] = ["min" => $min, "max" => $max];
        }

        continue;
    }

    // BLOCO DE SKIP
    if ($skipBloco && empty($pergunta['is_test_block'])) {
        continue;
    }

    $valor = $_POST[$chave] ?? "";

    if ($valor !== "") {
        $respostas[$chave] = $valor;
    }

    // Lógica de skip: "se responder NÃO, pula até o próximo bloco is_test_block"
    if (!empty($pergunta['skip_if_no']) && strtolower($valor) === "nao") {
        $skipBloco = true;
    }

    // Reinicia bloco quando encontrar marcador de teste
    if (!empty($pergunta['is_test_block'])) {
        $skipBloco = false;
    }
}


// --------------------------------------------------------------------
//  SALVA ARQUIVO JSON FINAL DO RELATÓRIO
// --------------------------------------------------------------------
$respostas['data_geracao'] = date('d/m/Y H:i:s');

$dir = "relatorios";
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

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
    <a href="produtos.php"><button>Voltar</button></a>
</div>

</body>
</html>
