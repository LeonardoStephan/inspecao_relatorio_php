<?php
session_start();

if (!isset($_GET['produto'])) {
    header("Location: produtos.php");
    exit;
}

$produtoKey = $_GET['produto'];

$produtosJson = @file_get_contents('data/produtos.json');
$produtos = $produtosJson ? json_decode($produtosJson, true) : [];

if (!isset($produtos[$produtoKey])) {
    echo "Produto inválido!";
    exit;
}

$produto = $produtos[$produtoKey];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatório - <?= htmlspecialchars($produto['nome']) ?></title>
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/script.js"></script>
</head>
<body>

<div class="container">
    <h2>Relatório: <?= htmlspecialchars($produto['nome']) ?></h2>

    <form action="gerar_relatorio.php" method="post" id="formRelatorio">
        <input type="hidden" name="produto" value="<?= htmlspecialchars($produtoKey) ?>">

        <?php foreach ($produto['perguntas'] as $i => $pergunta):
            $chave = $pergunta['chave'];
            $tipo = $pergunta['tipo'];
            $skip_if_no = !empty($pergunta['skip_if_no']) ? 'true' : 'false';
            $is_test_block = !empty($pergunta['is_test_block']) ? 'true' : 'false';
            $pulo_do_bloco = !empty($pergunta['pulo_do_bloco']) ? 'true' : 'false';
        ?>

        <div class="pergunta"
             id="pergunta_<?= $i ?>"
             data-chave="<?= htmlspecialchars($chave) ?>"
             data-tipo="<?= htmlspecialchars($tipo) ?>"
             data-skip="<?= $skip_if_no ?>"
             data-is-test-block="<?= $is_test_block ?>"
             data-pulo-bloco="<?= $pulo_do_bloco ?>"
             style="<?= $i === 0 ? 'display:block' : 'display:none' ?>">

            <label><?= htmlspecialchars(ucwords(str_replace("_", " ", $chave))) ?></label>

            <?php if ($tipo === 'binaria'): ?>
                <select name="<?= htmlspecialchars($chave) ?>">
                    <option value="">Selecione...</option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>

            <?php elseif ($tipo === 'nivel1'): ?>
                <select name="<?= htmlspecialchars($chave) ?>">
                    <option value="">Selecione...</option>
                    <option value="1">Nenhum</option>
                    <option value="2">Baixo</option>
                    <option value="3">Médio</option>
                    <option value="4">Alto</option>
                </select>

            <?php elseif ($tipo === 'nivel2'): ?>
                <select name="<?= htmlspecialchars($chave) ?>">
                    <option value="">Selecione...</option>
                    <option value="1">Nenhum</option>
                    <option value="2">Ruim</option>
                    <option value="3">Mediano</option>
                    <option value="4">Bom</option>
                </select>

            <?php elseif ($tipo === 'texto'): ?>
                <input type="text" name="<?= htmlspecialchars($chave) ?>">

            <?php elseif ($tipo === 'min_max' || $tipo === 'rssi'): ?>
                <div class="minmax-container">
                    <input type="number" name="<?= htmlspecialchars($chave) ?>_min" placeholder="Mínimo (30–70)" min="30" max="70" step="1">
                    <input type="number" name="<?= htmlspecialchars($chave) ?>_max" placeholder="Máximo (30–70)" min="30" max="70" step="1">
                </div>
                <div class="mensagem-erro" aria-live="polite" style="display:none;"></div>
            <?php endif; ?>

        </div>

        <?php endforeach; ?>

        <div id="botaoFinal" style="display:none; margin-top:20px;">
            <button type="submit">Gerar Relatório</button>
        </div>

    </form>

    <br>
    <a href="produtos.php"><button type="button">Voltar</button></a>
</div>
</body>
</html>
