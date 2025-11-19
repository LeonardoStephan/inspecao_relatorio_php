<?php
session_start();

// Checa produto selecionado
if (!isset($_GET['produto'])) {
    header("Location: produtos.php");
    exit;
}

$produtoKey = $_GET['produto'];

// Carrega JSON
$produtosJson = file_get_contents('data/produtos.json');
$produtos = json_decode($produtosJson, true);

// Checa se existe
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
</head>
<body>

<div class="container">
    <h2>Relatório: <?= htmlspecialchars($produto['nome']) ?></h2>

    <form action="gerar_relatorio.php" method="post" id="formRelatorio">

        <!-- Produto escolhido -->
        <input type="hidden" name="produto" value="<?= htmlspecialchars($produtoKey) ?>">

        <?php 
        $i = 0;
        foreach ($produto['perguntas'] as $pergunta):

            $chave = $pergunta['chave'];
            $tipo = $pergunta['tipo'];
            $skip_if_no = !empty($pergunta['skip_if_no']) ? 'true' : 'false';
            $is_test_block = !empty($pergunta['is_test_block']) ? 'true' : 'false';
        ?>

        <div class="pergunta"
            id="pergunta_<?= $i ?>"
            data-tipo="<?= htmlspecialchars($tipo) ?>"
            data-skip="<?= $skip_if_no ?>"
            data-is-test-block="<?= $is_test_block ?>"
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
                    <input 
                        type="number" 
                        name="<?= htmlspecialchars($chave) ?>_min"
                        placeholder="Mínimo (30–70)"
                        min="30"
                        max="70"
                        required
                        oninput="validarRSSI(this)"
                    >

                    <input 
                        type="number" 
                        name="<?= htmlspecialchars($chave) ?>_max"
                        placeholder="Máximo (30–70)"
                        min="30"
                        max="70"
                        required
                        oninput="validarRSSI(this)"
                    >
                </div>

                <div class="mensagem-erro" aria-live="polite" style="display:none;"></div>

            <?php endif; ?>

        </div>

        <?php 
        $i++;
        endforeach; 
        ?>

        <div id="botaoFinal" style="display:none; margin-top:20px;">
            <button type="submit">Gerar Relatório</button>
        </div>

    </form>

    <br>
    <a href="produtos.php"><button type="button">Voltar</button></a>

</div>

<script src="assets/js/script.js"></script>

<script>
/* === Validação RSSI / MIN_MAX === */
function validarRSSI(input) {

    let value = Number(input.value);

    // Limites fixos
    if (value < 30) input.value = 30;
    if (value > 70) input.value = 70;

    const container = input.closest(".minmax-container");
    const minInput = container.querySelector("input[name$='_min']");
    const maxInput = container.querySelector("input[name$='_max']");
    const msgErro = container.parentElement.querySelector(".mensagem-erro");

    const minValue = Number(minInput.value);
    const maxValue = Number(maxInput.value);

    msgErro.style.display = "none";

    if (!isNaN(minValue) && !isNaN(maxValue)) {
        if (maxValue < minValue) {
            msgErro.textContent = "O valor máximo não pode ser menor que o mínimo.";
            msgErro.style.display = "block";
        }
    }
}
</script>

</body>
</html>
