<?php
session_start();

$produtosJson = @file_get_contents('data/produtos.json');
$produtos = $produtosJson ? json_decode($produtosJson, true) : [];
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

    <?php if (!empty($produtos) && is_array($produtos)): ?>
        
        <div class="produtos-list">
            <?php foreach ($produtos as $key => $produto): ?>
                <a href="relatorio.php?produto=<?= urlencode($key) ?>">
                    <button type="button"><?= htmlspecialchars($produto['nome']) ?></button>
                </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <p>⚠️ Nenhum produto cadastrado ou JSON inválido.</p>
    <?php endif; ?>

    <br><br>

    <a href="informacoes.php"><button type="button">Voltar</button></a>

</div>

</body>
</html>
