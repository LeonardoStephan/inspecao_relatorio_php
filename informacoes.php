<?php
session_start();

$anoAtual = date("Y");

// Recupera valores salvos na sessão
$empresa     = $_SESSION['empresa']     ?? '';
$opCompleta  = $_SESSION['op']          ?? '';
$data_inicio = $_SESSION['data_inicio'] ?? '';
$data_fim    = $_SESSION['data_fim']    ?? '';
$previsao    = $_SESSION['previsao']    ?? '';

// Extrai apenas os 5 dígitos da OP se já foi salva antes
$opSomenteNumero = "";
if (!empty($opCompleta) && strpos($opCompleta, "/") !== false) {
    $opSomenteNumero = explode("/", $opCompleta)[1];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Informações Iniciais</title>
<link rel="stylesheet" href="assets/css/style.css">

<script>
// ---------- Salvar sessão em tempo real ----------
function salvarResposta(chave, valor) {
    fetch('salvar_resposta.php', {
        method: "POST",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: "chave=" + encodeURIComponent(chave) +
              "&valor=" + encodeURIComponent(valor)
    });
}

// ---------- Validação ----------
function validarForm() {

    // Valida OP (5 dígitos)
    const op = document.getElementById("op").value;
    const erro = document.getElementById("erroOP");

    if (!/^\d{5}$/.test(op)) {
        erro.style.display = "block";
        return false;
    }
    erro.style.display = "none";

    // Valida datas
    const ini = document.querySelector("input[name='data_inicio']").value;
    const fim = document.querySelector("input[name='data_fim']").value;
    const prev = document.querySelector("input[name='previsao']").value;

    if (ini && fim && fim < ini) {
        alert("A data de conclusão não pode ser antes da data de início!");
        return false;
    }

    if (ini && prev && prev < ini) {
        alert("A previsão não pode ser antes da data de início!");
        return false;
    }

    return true;
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("input, select").forEach(el => {
        el.addEventListener("change", () => salvarResposta(el.name, el.value));
    });
});
</script>
</head>

<body>
<div class="container">

    <h1>Início do Relatório</h1>

    <form action="produtos.php" method="post" onsubmit="return validarForm();">

        <h3>Por qual empresa?</h3>
        <select name="empresa" required>
            <option value="">Selecione</option>
            <option value="Marchi" <?= $empresa === "Marchi" ? "selected" : "" ?>>Marchi</option>
            <option value="GS" <?= $empresa === "GS" ? "selected" : "" ?>>GS</option>
        </select>

        <h3>Digite o número da Ordem de Produção:</h3>
        <div>
            <span><?= htmlspecialchars($anoAtual) ?>/</span>
            <input style="width:100px;" type="text" name="op" id="op" maxlength="5"
                oninput="this.value = this.value.replace(/[^0-9]/g,'');"
                required placeholder="00000"
                value="<?= htmlspecialchars($opSomenteNumero) ?>">
        </div>
        <div id="erroOP" style="color:red; display:none;">
            A OP deve ter 5 dígitos numéricos.
        </div>

        <h3>Dia do Início:</h3>
        <input type="date" name="data_inicio" value="<?= htmlspecialchars($data_inicio) ?>" required>

        <h3>Dia de Conclusão:</h3>
        <input type="date" name="data_fim" value="<?= htmlspecialchars($data_fim) ?>" required>

        <h3>Previsão de Conclusão:</h3>
        <input type="date" name="previsao" value="<?= htmlspecialchars($previsao) ?>" required>

        <br><br>

        <div>
            <a href="index.php"><button type="button">Voltar</button></a>
            <button type="submit">Próximo</button>
        </div>

    </form>

</div>
</body>
</html>
