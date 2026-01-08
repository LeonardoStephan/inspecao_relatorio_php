<?php
session_start();

$anoAtual = date("Y");
$hoje = date('Y-m-d');

// Valores da sessão (se existirem)
$empresa     = $_SESSION['empresa']     ?? '';
$opCompleta  = $_SESSION['op']          ?? '';
$data_inicio = $_SESSION['data_inicio'] ?? $hoje;
$data_fim    = $_SESSION['data_fim']    ?? $hoje;
$previsao    = $_SESSION['previsao']    ?? $hoje;

// Extrai somente o número da OP
$opSomenteNumero = "";
if (!empty($opCompleta) && strpos($opCompleta, "/") !== false) {
    $opSomenteNumero = explode("/", $opCompleta)[1];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Informações Iniciais</title>
<link rel="stylesheet" href="assets/css/style.css">

<script>
function salvarResposta(chave, valor) {
    fetch('salvar_resposta.php', {
        method: "POST",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: "chave=" + encodeURIComponent(chave) +
              "&valor=" + encodeURIComponent(valor)
    });
}

function validarForm() {
    const op = document.getElementById("op").value;
    const erro = document.getElementById("erroOP");

    if (!/^\d{5}$/.test(op)) {
        erro.style.display = "block";
        return false;
    }
    erro.style.display = "none";

    const ini  = document.getElementById("data_inicio").value;
    const fim  = document.getElementById("data_fim").value;
    const prev = document.getElementById("previsao").value;

    if (ini && fim && fim < ini) {
        alert("A data de conclusão não pode ser antes da data de início!");
        return false;
    }
    if (ini < fim && ini && fim && fim == prev) {
        alert("A data de conclusão não pode ultrapassar a data de hoje! ");
        return false;
    } 
    if (ini && prev && prev < ini) {
        alert("A previsão não pode ser antes da data de início!");
        return false;
    }

    return true;
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        if (!validarForm()) return;

        const campos = ["empresa", "op", "data_inicio", "data_fim", "previsao"];
        for (let nome of campos) {
            const el = document.querySelector(`[name="${nome}"]`);
            if (el) {
                let valor = el.value;
                if (nome === "op") {
                    valor = valor.replace(/\D/g,''); // OP sem ano
                }
                await fetch('salvar_resposta.php', {
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: "chave=" + encodeURIComponent(nome) + "&valor=" + encodeURIComponent(valor)
                });
            }
        }

        // Depois de salvar, envia para produtos.php
        form.submit();
    });
});

</script>
</head>

<body>
<div class="container">
    <h1>Início do Relatório</h1>

    <form action="produtos.php" method="get" onsubmit="return validarForm();">

        <h3>Por qual empresa?</h3>
        <select name="empresa" id="empresa" required>
            <option value="">Selecione</option>
            <option value="Marchi" <?= $empresa === "Marchi" ? "selected" : "" ?>>Marchi</option>
            <option value="GS" <?= $empresa === "GS" ? "selected" : "" ?>>GS</option>
        </select>

        <h3>Digite o número da Ordem de Produção:</h3>
        <div>
            <span><?= htmlspecialchars($anoAtual) ?>/</span>
            <input
                style="width:100px;"
                type="text"
                name="op"
                id="op"
                maxlength="5"
                placeholder="00000"
                value="<?= htmlspecialchars($opSomenteNumero) ?>"
                oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                required
            >
        </div>

        <div id="erroOP" style="color:red; display:none;">
            A OP deve ter 5 dígitos numéricos.
        </div>

        <h3>Dia do Início:</h3>
        <input type="date" name="data_inicio" id="data_inicio" value="<?= $data_inicio ?>" required>

        <h3>Dia de Conclusão:</h3>
        <input type="date" name="data_fim" id="data_fim" value="<?= $data_fim ?>" required>

        <h3>Previsão de Conclusão:</h3>
        <input type="date" name="previsao" id="previsao" value="<?= $previsao ?>" required>

        <br><br>
        <div>
            <a href="index.php">
                <button type="button">Voltar</button>
            </a>
            <button type="submit">Próximo</button>
        </div>
    </form>
</div>
</body>
</html>
