<?php
session_start();

$produtosJson = file_get_contents(__DIR__ . '/data/produtos.json');
$produtos = json_decode($produtosJson, true);

if (!$produtos || !is_array($produtos)) {
    die("Erro ao carregar produtos.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Seleção de Produto</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <h2>Selecione o Produto</h2>

    <div class="produtos-list">
        <?php foreach ($produtos as $key => $produto): ?>
            <a href="relatorio.php?produto=<?= urlencode($key) ?>">
                <button type="button">
                    <?= htmlspecialchars($produto['nome']) ?>
                </button>
            </a>
        <?php endforeach; ?>
    </div>

    <br>
    <a href="informacoes.php">
        <button type="button">Voltar</button>
    </a>
</div>

</body>
</html>
