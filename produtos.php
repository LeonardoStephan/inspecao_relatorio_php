<?php
session_start();
$anoAtual = date("Y");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['empresa'])) $_SESSION['empresa'] = $_POST['empresa'];
    if (isset($_POST['op'])) $_SESSION['op'] = $anoAtual . '/' . str_pad(preg_replace('/\D/', '', $_POST['op']), 5, '0', STR_PAD_LEFT);
    if (isset($_POST['data_inicio'])) $_SESSION['data_inicio'] = $_POST['data_inicio'];
    if (isset($_POST['data_fim'])) $_SESSION['data_fim'] = $_POST['data_fim'];
    if (isset($_POST['previsao'])) $_SESSION['previsao'] = $_POST['previsao'];
}

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
