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
<<<<<<< HEAD
=======

>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
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
<<<<<<< HEAD
            // pulo_do_bloco em JSON -> transforma para data-pulo-bloco
            $pulo_do_bloco = !empty($pergunta['pulo_do_bloco']) ? 'true' : 'false';
=======
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
        ?>

        <div class="pergunta"
            id="pergunta_<?= $i ?>"
            data-tipo="<?= htmlspecialchars($tipo) ?>"
            data-skip="<?= $skip_if_no ?>"
            data-is-test-block="<?= $is_test_block ?>"
<<<<<<< HEAD
            data-pulo-bloco="<?= $pulo_do_bloco ?>"
=======
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
            style="<?= $i === 0 ? 'display:block' : 'display:none' ?>">

            <label><?= htmlspecialchars(ucwords(str_replace("_", " ", $chave))) ?></label>

            <?php if ($tipo === 'binaria'): ?>

<<<<<<< HEAD
                <select name="<?= htmlspecialchars($chave) ?>" data-chave="<?= htmlspecialchars($chave) ?>">
=======
                <select name="<?= htmlspecialchars($chave) ?>">
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
                    <option value="">Selecione...</option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>

            <?php elseif ($tipo === 'nivel1'): ?>

<<<<<<< HEAD
                <select name="<?= htmlspecialchars($chave) ?>" data-chave="<?= htmlspecialchars($chave) ?>">
=======
                <select name="<?= htmlspecialchars($chave) ?>">
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
                    <option value="">Selecione...</option>
                    <option value="1">Nenhum</option>
                    <option value="2">Baixo</option>
                    <option value="3">Médio</option>
                    <option value="4">Alto</option>
                </select>

            <?php elseif ($tipo === 'nivel2'): ?>

<<<<<<< HEAD
                <select name="<?= htmlspecialchars($chave) ?>" data-chave="<?= htmlspecialchars($chave) ?>">
=======
                <select name="<?= htmlspecialchars($chave) ?>">
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
                    <option value="">Selecione...</option>
                    <option value="1">Nenhum</option>
                    <option value="2">Ruim</option>
                    <option value="3">Mediano</option>
                    <option value="4">Bom</option>
                </select>

            <?php elseif ($tipo === 'texto'): ?>

<<<<<<< HEAD
                <input type="text" name="<?= htmlspecialchars($chave) ?>" data-chave="<?= htmlspecialchars($chave) ?>">

            <?php elseif ($tipo === 'min_max' || $tipo === 'rssi'): ?>

                <div class="minmax-container" data-chave="<?= htmlspecialchars($chave) ?>">
=======
                <input type="text" name="<?= htmlspecialchars($chave) ?>">

            <?php elseif ($tipo === 'min_max' || $tipo === 'rssi'): ?>

                <div class="minmax-container">
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
                    <input 
                        type="number" 
                        name="<?= htmlspecialchars($chave) ?>_min"
                        placeholder="Mínimo (30–70)"
<<<<<<< HEAD
                        min="0"
                        step="1"
=======
                        min="30"
                        max="70"
                        required
                        oninput="validarRSSI(this)"
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
                    >

                    <input 
                        type="number" 
                        name="<?= htmlspecialchars($chave) ?>_max"
                        placeholder="Máximo (30–70)"
<<<<<<< HEAD
                        min="0"
                        step="1"
=======
                        min="30"
                        max="70"
                        required
                        oninput="validarRSSI(this)"
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
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

<<<<<<< HEAD
<!-- Reforços específicos para montagem/teste e validação RSSI (não conflitam com script.js) -->
<script>
(function () {
    // Utilitários
    const perguntas = Array.from(document.querySelectorAll('.pergunta'));
    const botaoFinal = document.getElementById('botaoFinal');

    function indexOfQuestionByKey(keyStart) {
        return perguntas.findIndex(p => (p.querySelector('[data-chave]') || {}).dataset && ((p.querySelector('[data-chave]')||{}).dataset.chave || '').startsWith(keyStart));
    }

    // encontra índice do teste_aplicavel (primeira pergunta com data-is-test-block="true")
    const testIndex = perguntas.findIndex(p => p.dataset.isTestBlock === "true");
    const montagemIndex = 0; // você estruturou montagem_aplicavel como primeira pergunta

    function show(el){ el.style.display = 'block'; }
    function hide(el){ el.style.display = 'none'; }

    function campoEstaPreenchido(p) {
        // similar ao script.js: verifica select/text/number/minmax
        const sel = p.querySelector('select');
        if (sel && sel.value === '') return false;

        const txt = p.querySelector('input[type="text"]');
        if (txt && txt.value.trim() === '') return false;

        const min = p.querySelector("input[name$='_min']");
        const max = p.querySelector("input[name$='_max']");
        if (min || max) {
            // se existe par min/max -> ambos devem estar preenchidos e válidos
            const msg = p.querySelector('.mensagem-erro');
            const minVal = min ? min.value.trim() : '';
            const maxVal = max ? max.value.trim() : '';
            if (minVal === '' || maxVal === '') return false;
            const nmin = Number(minVal);
            const nmax = Number(maxVal);
            if (isNaN(nmin) || isNaN(nmax)) {
                if (msg) { msg.textContent = 'Digite números válidos.'; msg.style.display='block'; }
                return false;
            }
            // limites fixos 30..70
            if (nmin < 30 || nmin > 70 || nmax < 30 || nmax > 70) {
                if (msg) { msg.textContent = 'Valores válidos: 30 a 70.'; msg.style.display='block'; }
                return false;
            }
            if (nmax < nmin) {
                if (msg) { msg.textContent = 'O máximo não pode ser menor que o mínimo.'; msg.style.display='block'; }
                return false;
            }
            if (msg) msg.style.display='none';
        }

        // numbers simples
        const nums = p.querySelectorAll('input[type="number"]');
        for (let n of nums) {
            if (n.value.trim() === '') return false;
        }

        return true;
    }

    function atualizarVisibilidadeAposMontagemTeste() {
        // primeiro chama a função principal do seu script (se existir) para manter fluxo "uma por vez"
        if (typeof window.avancarFluxo === 'function') {
            try { window.avancarFluxo(); } catch(e){ /* ignore */ }
        }

        // lê montagem e teste
        const montagemSel = perguntas[montagemIndex].querySelector('select');
        const montagemVal = montagemSel ? montagemSel.value.toLowerCase() : '';

        let testeSel = null, testeVal = '';
        if (testIndex !== -1) {
            testeSel = perguntas[testIndex].querySelector('select');
            testeVal = testeSel ? testeSel.value.toLowerCase() : '';
        }

        // 1) Se montagem = nao -> ocultar todas as perguntas entre montagemIndex+1 e testIndex-1 (se testIndex existe)
        if (montagemVal === 'nao') {
            for (let i = montagemIndex + 1; i < (testIndex === -1 ? perguntas.length : testIndex); i++) {
                hide(perguntas[i]);
            }
            // exibir apenas a primeira pergunta do bloco de teste (se existir)
            if (testIndex !== -1) {
                show(perguntas[testIndex]);
            }
        } else {
            // montagem = sim -> deixar o script.js controlar (reexibir se necessário)
            // para segurança, reexibir as perguntas entre montagem e teste (serão escondidas pelo fluxo se não respondidas)
            for (let i = montagemIndex + 1; i < (testIndex === -1 ? perguntas.length : testIndex); i++) {
                show(perguntas[i]);
            }
        }

        // 2) Se teste = nao -> ocultar todas as perguntas seguintes ao teste, exceto a pergunta de embalagem_aplicavel (se existir)
        if (testeSel && testeVal === 'nao') {
            // encontra index da embalagem_aplicavel (se houver)
            const embalagemIndex = perguntas.findIndex(p => {
                const q = (p.querySelector('[data-chave]') || {}).dataset.chave || '';
                return q.toLowerCase().includes('embalagem');
            });

            for (let i = testIndex + 1; i < perguntas.length; i++) {
                // se existe embalagemIndex e é esta, mostra, senão esconde
                if (embalagemIndex !== -1 && i === embalagemIndex) {
                    show(perguntas[i]);
                } else {
                    hide(perguntas[i]);
                }
            }
        }

        // 3) Botão final: só aparece se pelo menos UMA das condições:
        //    - montagem = sim (e todas perguntas visíveis respondidas)
        //    - teste = sim (e todas perguntas visíveis respondidas)
        // Se ambos nao -> não mostrar

        // verifica se todas as perguntas visíveis estão preenchidas
        const todasVisiveisPreenchidas = perguntas.every(p => {
            if (p.style.display === 'none') return true; // ignorar ocultas
            return campoEstaPreenchido(p);
        });

        const montagemSim = montagemSel && montagemSel.value.toLowerCase() === 'sim';
        const testeSim = testeSel && testeSel.value.toLowerCase() === 'sim';

        if ((montagemSim || testeSim) && todasVisiveisPreenchidas) {
            botaoFinal.style.display = 'block';
        } else {
            botaoFinal.style.display = 'none';
        }
    }

    // attach listeners specifically for montagem and teste selects and for min/max inputs
    perguntas.forEach((p, idx) => {
        p.querySelectorAll('select, input').forEach(el => {
            el.addEventListener('change', function () {
                // small debounce para deixar script.js executar primeiro
                setTimeout(atualizarVisibilidadeAposMontagemTeste, 50);
            });
            el.addEventListener('input', function () {
                setTimeout(atualizarVisibilidadeAposMontagemTeste, 50);
            });
        });
    });

    // run once at load
    setTimeout(atualizarVisibilidadeAposMontagemTeste, 100);
})();
</script>

<!-- Ajuste final: validação RSSI no submit -->
<script>
document.getElementById('formRelatorio').addEventListener('submit', function(e){
    // valida todos os pares min/max de forma final antes do envio
    const containers = document.querySelectorAll('.minmax-container');
    for (let c of containers) {
        const min = c.querySelector("input[name$='_min']");
        const max = c.querySelector("input[name$='_max']");
        const p = c.closest('.pergunta');
        const msg = p.querySelector('.mensagem-erro');
        if (!min || !max) continue;
        const nmin = Number(min.value);
        const nmax = Number(max.value);
        if (min.value.trim() === '' && max.value.trim() === '') continue; // opcional: permitir ambos vazios
        if (isNaN(nmin) || isNaN(nmax)) {
            e.preventDefault();
            if (msg) { msg.textContent = 'Digite números válidos.'; msg.style.display='block'; }
            min.focus();
            return false;
        }
        if (nmin < 30 || nmin > 70 || nmax < 30 || nmax > 70) {
            e.preventDefault();
            if (msg) { msg.textContent = 'Valores válidos: 30 a 70.'; msg.style.display='block'; }
            min.focus();
            return false;
        }
        if (nmax < nmin) {
            e.preventDefault();
            if (msg) { msg.textContent = 'O máximo não pode ser menor que o mínimo.'; msg.style.display='block'; }
            max.focus();
            return false;
        }
    }
});
=======
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
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
</script>

</body>
</html>
