/* ============================================================
   script.js — Fluxo estável para relatorio.php (versão corrigida)
   ============================================================ */

/* ---------------------------
   BLOCO A — RESTAURAR / SALVAR INFO
   --------------------------- */
document.addEventListener("DOMContentLoaded", function () {
    const camposInfo = ["empresa", "op", "data_inicio", "data_fim", "previsao"];
    camposInfo.forEach(id => {
        const el = document.getElementById(id);
        if (el && sessionStorage.getItem(id)) el.value = sessionStorage.getItem(id);
    });
    camposInfo.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener("input", () => sessionStorage.setItem(id, el.value));
    });
});


/* ---------------------------
   BLOCO B — RESTAURAR / SALVAR PERGUNTAS
   --------------------------- */
document.addEventListener("DOMContentLoaded", function () {
    const perguntas = document.querySelectorAll("[data-chave]");
    if (!perguntas || perguntas.length === 0) return;

    perguntas.forEach(p => {
        const chave = p.dataset.chave;
        const campo = document.querySelector(`[name='${chave}']`);
        const min = document.querySelector(`[name='${chave}_min']`);
        const max = document.querySelector(`[name='${chave}_max']`);

        if (campo) {
            const stored = sessionStorage.getItem(chave);
            if (stored !== null) campo.value = stored;
            campo.addEventListener("input", () => sessionStorage.setItem(chave, campo.value));
            campo.addEventListener("change", () => sessionStorage.setItem(chave, campo.value));
        }

        if (min && max) {
            const smin = sessionStorage.getItem(chave + "_min");
            const smax = sessionStorage.getItem(chave + "_max");
            if (smin !== null) min.value = smin;
            if (smax !== null) max.value = smax;
            min.addEventListener("input", () => sessionStorage.setItem(chave + "_min", min.value));
            max.addEventListener("input", () => sessionStorage.setItem(chave + "_max", max.value));
            min.addEventListener("change", () => sessionStorage.setItem(chave + "_min", min.value));
            max.addEventListener("change", () => sessionStorage.setItem(chave + "_max", max.value));
        }
    });
});


/* ---------------------------
   BLOCO C — VALIDAÇÃO MIN/MAX + RSSI
   --------------------------- */
function validarRSSI(input) {
    let v = Number(input.value);
    if (isNaN(v)) return;
    if (v < 30) input.value = 30;
    if (v > 70) input.value = 70;

    const cont = input.closest(".minmax-container");
    if (!cont) return;
    const min = cont.querySelector("input[name$='_min']");
    const max = cont.querySelector("input[name$='_max']");
    if (!min || !max) return;
    if (Number(max.value) < Number(min.value)) max.value = min.value;
}

function validaMinMaxInline(div) {
    const min = div.querySelector("input[name$='_min']");
    const max = div.querySelector("input[name$='_max']");
    const msg = div.querySelector(".mensagem-erro");
    if (!min || !max) return true;
    if (min.value === "" || max.value === "") { if (msg) msg.style.display = "none"; return false; }

    const nmin = Number(min.value), nmax = Number(max.value);
    if (isNaN(nmin) || isNaN(nmax)) { if (msg) msg.style.display = "none"; return false; }
    if (nmin < 30 || nmin > 70 || nmax < 30 || nmax > 70) {
        if (msg) { msg.textContent = "Valores válidos: 30 a 70."; msg.style.display = "block"; }
        return false;
    }
    if (nmax < nmin) {
        if (msg) { msg.textContent = "Máximo não pode ser menor que o mínimo."; msg.style.display = "block"; }
        return false;
    }
    if (msg) msg.style.display = "none";
    return true;
}


/* ---------------------------
   UTIL — (DES)ABILITAR INPUTS DO BLOCO (sem apagar valores)
   --------------------------- */
function setDisabledForBlock(blockDiv, disabled) {
    blockDiv.querySelectorAll("input, select, textarea").forEach(el => {
        el.disabled = !!disabled;
        if (disabled) el.classList.add("disabled-temporario");
        else el.classList.remove("disabled-temporario");
    });
}

/* ---------------------------
   HELPERS PARA LER ATRIBUTOS (robusto)
   - cobre data-skip, data-skip-if-no, data-is-test-block, data-pulo-bloco
   --------------------------- */
function getBoolAttr(el, ...possibleNames) {
    // tenta dataset (camelCase via dataset), depois attribute raw
    for (const name of possibleNames) {
        // dataset lookup (camelCase)
        const dsName = name.replace(/-([a-z])/g, g => g[1].toUpperCase()); // e.g. skip-if-no -> skipIfNo
        if (typeof el.dataset[dsName] !== "undefined") {
            if (String(el.dataset[dsName]).toLowerCase() === "true") return true;
            if (String(el.dataset[dsName]).toLowerCase() === "false") return false;
        }
        // raw attribute fallback
        const raw = el.getAttribute("data-" + name);
        if (raw !== null) {
            if (String(raw).toLowerCase() === "true") return true;
            if (String(raw).toLowerCase() === "false") return false;
        }
    }
    return false;
}


/* ---------------------------
   BLOCO D — FLUXO PRINCIPAL (corrigido e robusto)
   --------------------------- */
document.addEventListener("DOMContentLoaded", function () {

    const perguntas = Array.from(document.querySelectorAll(".pergunta"));
    const botaoFinal = document.getElementById("botaoFinal");
    if (!perguntas || perguntas.length === 0) return;

    // Inicial: mostra só a primeira e garante disabled nos demais
    perguntas.forEach((p, i) => {
        if (i === 0) { p.style.display = "block"; setDisabledForBlock(p, false); }
        else { p.style.display = "none"; setDisabledForBlock(p, true); }
    });

    function perguntaPreenchida(p) {
        if (!p || p.style.display === "none" || p.querySelectorAll("input,select,textarea").length === 0) return true;
        // Ignora controles desabilitados
        const sel = Array.from(p.querySelectorAll("select")).find(s => !s.disabled);
        if (sel && sel.value.trim() === "") return false;

        const txt = Array.from(p.querySelectorAll("input[type='text']")).find(i => !i.disabled);
        if (txt && txt.value.trim() === "") return false;

        const min = p.querySelector("input[name$='_min']");
        const max = p.querySelector("input[name$='_max']");
        if (min && max && !min.disabled && !max.disabled) return validaMinMaxInline(p);

        const nums = Array.from(p.querySelectorAll("input[type='number']")).filter(n => !n.disabled);
        for (let n of nums) {
            if (n.value.trim() === "") return false;
        }

        return true;
    }

    function avancarFluxo() {
        // Encontra índices relevantes
        const indexMontagem = perguntas.findIndex(p => getBoolAttr(p, "skip-if-no", "skip"));
        const indexTeste    = perguntas.findIndex(p => getBoolAttr(p, "is-test-block", "is_test_block"));

        // determina se devemos pular montagem / teste
        let skipMontagem = false;
        let skipTeste = false;

        if (indexMontagem !== -1) {
            const sel = perguntas[indexMontagem].querySelector("select");
            if (sel && !sel.disabled && String(sel.value).toLowerCase() === "nao") skipMontagem = true;
        }

        if (indexTeste !== -1) {
            const sel = perguntas[indexTeste].querySelector("select");
            if (sel && !sel.disabled && String(sel.value).toLowerCase() === "nao") skipTeste = true;
        }

        // percorre perguntas e decide exibição + disabled
        for (let i = 0; i < perguntas.length; i++) {
            const p = perguntas[i];

            // regra: mantenha a pergunta marcadora (indexMontagem / indexTeste) sempre visível
            // hide montagem blocos (após marcador até antes do teste)
            if (skipMontagem && indexMontagem !== -1 && i > indexMontagem && (indexTeste === -1 ? true : i < indexTeste)) {
                p.style.display = "none";
                setDisabledForBlock(p, true);
                continue;
            }

            // hide perguntas de teste (após o marcador de teste)
            if (skipTeste && indexTeste !== -1 && i > indexTeste) {
                p.style.display = "none";
                setDisabledForBlock(p, true);
                continue;
            }

            // Caso contrário, mostrar (pode ser marcador ou pergunta normal)
            p.style.display = "block";
            setDisabledForBlock(p, false);

            // Se esta pergunta visível não estiver preenchida, bloqueia próximas
            if (!perguntaPreenchida(p)) {
                for (let j = i + 1; j < perguntas.length; j++) {
                    perguntas[j].style.display = "none";
                    setDisabledForBlock(perguntas[j], true);
                }
                botaoFinal.style.display = "none";
                return;
            }
        }

        // Se chegou aqui, todas visíveis estão preenchidas
        botaoFinal.style.display = "block";
    }

    // Adiciona listeners (input & change) em todos os controles de cada pergunta
    perguntas.forEach(p => {
        p.querySelectorAll("select, input, textarea").forEach(el => {
            el.addEventListener("input", avancarFluxo);
            el.addEventListener("change", avancarFluxo);
        });
    });

    // Execução inicial
    avancarFluxo();
});
