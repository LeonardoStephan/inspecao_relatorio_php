<?php
session_start();

if (!isset($_GET['produto'])) {
    header("Location: produtos.php");
    exit;
}

$produtoKey = $_GET['produto'];
$produtosJson = file_get_contents('data/produtos.json');
$produtos = json_decode($produtosJson, true);

if (!isset($produtos[$produtoKey])) {
    echo "Produto inválido!";
    exit;
}

$produto = $produtos[$produtoKey];
$blocos = $produto['blocos'] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatório - <?= htmlspecialchars($produto['nome']) ?></title>
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/script.js" defer></script>
</head>
<body>

<div class="container">
    <h2>Relatório: <?= htmlspecialchars($produto['nome']) ?></h2>

    <form action="gerar_relatorio.php" method="post" id="formRelatorio">
        <input type="hidden" name="produto" value="<?= htmlspecialchars($produtoKey) ?>">

        <?php foreach ($blocos as $bloco): ?>
            <div class="bloco" data-bloco="<?= htmlspecialchars($bloco['id']) ?>">

                <h3><?= htmlspecialchars($bloco['titulo']) ?></h3>

                <!-- Pergunta de Aplicável -->
                <?php if (isset($bloco['pergunta_aplicavel'])): 
                    $apChave = $bloco['pergunta_aplicavel']['chave'];
                ?>
                    <div class="pergunta">
                        <label><?= htmlspecialchars(ucwords(str_replace("_", " ", $apChave))) ?>?</label>
                        <select name="<?= htmlspecialchars($apChave) ?>" class="aplicavel">
                            <option value="">Selecione...</option>
                            <option value="sim">Sim</option>
                            <option value="nao">Não</option>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Perguntas do bloco -->
                <?php foreach ($bloco['perguntas'] as $pergunta):
                    $chave = $pergunta['chave'];
                    $tipo  = $pergunta['tipo'];
                ?>
                    <div class="pergunta">
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

                        <?php elseif ($tipo === 'nivel3'): ?>
                            <select name="<?= htmlspecialchars($chave) ?>">
                                <option value="">Selecione...</option>
                                <option value="1">Bruno</option>
                                <option value="2">Leonardo</option>
                                <option value="3">Matheus</option>
                                <option value="4">Robert</option>
                                <option value="5">Thiago</option>

                            </select>

                        <?php elseif ($tipo === 'texto'): ?>
                            <input type="text" name="<?= htmlspecialchars($chave) ?>">

                        <?php elseif ($tipo === 'min_max' || $tipo === 'rssi'): ?>
                            <div class="minmax-container">
                                <input type="number" name="<?= htmlspecialchars($chave) ?>_min" placeholder="Mínimo (30–70)" min="30" max="70" step="1">
                                <input type="number" name="<?= htmlspecialchars($chave) ?>_max" placeholder="Máximo (30–70)" min="30" max="70" step="1">
                            </div>
                            <div class="mensagem-erro"></div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>

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
