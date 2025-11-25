<?php
session_start();

<<<<<<< HEAD
$produtosJson = @file_get_contents('data/produtos.json');
$produtos = $produtosJson ? json_decode($produtosJson, true) : [];
=======
$produtosJson = file_get_contents('data/produtos.json');
$produtos = json_decode($produtosJson, true);
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Seleção de Produto</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<<<<<<< HEAD

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

=======
<div class="container">
    <h2>Selecione o Produto</h2>

    <?php if (!empty($produtos)): ?>
        <?php foreach ($produtos as $key => $produto): ?>
            <a href="relatorio.php?produto=<?= urlencode($key) ?>">
                <button type="button"><?= htmlspecialchars($produto['nome']) ?></button>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>⚠️ Nenhum produto cadastrado.</p>
    <?php endif; ?>

    <br><br>
    <a href="informacoes.php"><button type="button">Voltar</button></a>
</div>
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
</body>
</html>
