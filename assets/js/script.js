/* ============================================================
   BLOCO A — SALVAR E RESTAURAR EMPRESA / OP / DATAS
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {

    const camposInfo = ["empresa", "op", "data_inicio", "data_fim", "previsao"];

    // --- Restaura valores automaticamente ao voltar ---
    camposInfo.forEach(id => {
        const el = document.getElementById(id);
        if (el && sessionStorage.getItem(id)) {
            el.value = sessionStorage.getItem(id);
        }
    });

    // --- Salva automaticamente quando digitar ---
    camposInfo.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        el.addEventListener("input", () => {
            sessionStorage.setItem(id, el.value);
        });
    });
});


/* ============================================================
   BLOCO B — SALVAR E RESTAURAR PERGUNTAS DO RELATÓRIO
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {

    const perguntas = document.querySelectorAll("[data-chave]");

    if (perguntas.length === 0) return;

    // Restaurar valores
    perguntas.forEach(p => {
        const chave = p.dataset.chave;
        const campomin = document.querySelector(`[name='${chave}_min']`);
        const campomax = document.querySelector(`[name='${chave}_max']`);
        const campo = document.querySelector(`[name='${chave}']`);

        // min/max
        if (campomin && campomax) {
            if (sessionStorage.getItem(chave + "_min"))
                campomin.value = sessionStorage.getItem(chave + "_min");
            if (sessionStorage.getItem(chave + "_max"))
                campomax.value = sessionStorage.getItem(chave + "_max");

            campomin.addEventListener("input", () => {
                sessionStorage.setItem(chave + "_min", campomin.value);
            });

            campomax.addEventListener("input", () => {
                sessionStorage.setItem(chave + "_max", campomax.value);
            });
        }

        // texto/select
        if (campo) {
            if (sessionStorage.getItem(chave))
                campo.value = sessionStorage.getItem(chave);

            campo.addEventListener("input", () => {
                sessionStorage.setItem(chave, campo.value);
            });
        }
    });
});


/* ============================================================
   BLOCO C — VALIDAÇÃO MIN/MAX + RSSI
   ============================================================ */

function validarRSSI(input) {
    let v = Number(input.value);
    if (v < 30) input.value = 30;
    if (v > 70) input.value = 70;

    const cont = input.closest(".minmax-container");
    if (!cont) return;

    const min = cont.querySelector("input[name$='_min']");
    const max = cont.querySelector("input[name$='_max']");

    if (Number(max.value) < Number(min.value)) {
        max.value = min.value;
    }
}

function validaMinMaxInline(perguntaDiv) {
    const min = perguntaDiv.querySelector("input[name$='_min']");
    const max = perguntaDiv.querySelector("input[name$='_max']");
    const msg = perguntaDiv.querySelector(".mensagem-erro");

    if (!min || !max) return true;

    if (min.value === "" || max.value === "") {
        msg.style.display = "none";
        return false;
    }

    let nmin = Number(min.value);
    let nmax = Number(max.value);

    if (nmin < 30 || nmin > 70 || nmax < 30 || nmax > 70) {
        msg.textContent = "Valores válidos: 30 a 70.";
        msg.style.display = "block";
        return false;
    }

    if (nmax < nmin) {
        msg.textContent = "Máximo não pode ser menor que o mínimo.";
        msg.style.display = "block";
        return false;
    }

    msg.style.display = "none";
    return true;
}


/* ============================================================
   BLOCO D — LÓGICA PRINCIPAL DO FORMULÁRIO (FLUXO)
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {

    const perguntas = Array.from(document.querySelectorAll(".pergunta"));
    const botaoFinal = document.getElementById("botaoFinal");

    if (perguntas.length === 0) return;

    // Mostrar só a primeira
    perguntas.forEach((p, i) => p.style.display = i === 0 ? "block" : "none");

    function perguntaPreenchida(p) {
        const sel = p.querySelector("select");
        if (sel && sel.value === "") return false;

        const txt = p.querySelector("input[type='text']");
        if (txt && txt.value.trim() === "") return false;

        const min = p.querySelector("input[name$='_min']");
        const max = p.querySelector("input[name$='_max']");
        if (min && max) return validaMinMaxInline(p);

        const nums = p.querySelectorAll("input[type='number']");
        for (let n of nums) if (n.value.trim() === "") return false;

        return true;
    }

    function encontrarInicioTeste() {
        return perguntas.findIndex(p => p.dataset.isTestBlock === "true");
    }

    function avancarFluxo() {
        const testStart = encontrarInicioTeste();
        let skipMontagem = false;
        let skipTeste = false;

        // skip da montagem
        perguntas.forEach((p, i) => {
            const sel = p.querySelector("select");
            if (!sel) return;

            if (p.dataset.skip === "true" && sel.value.toLowerCase() === "nao") {
                if (i < testStart) skipMontagem = true;
            }
        });

        // skip do teste
        if (testStart !== -1) {
            const selTeste = perguntas[testStart].querySelector("select");
            if (selTeste && selTeste.value.toLowerCase() === "nao") {
                skipTeste = true;
            }
        }

        for (let i = 0; i < perguntas.length; i++) {
            const p = perguntas[i];

            // esconder montagem
            if (skipMontagem && i > 0 && i < testStart) {
                p.style.display = "none";
                continue;
            }

            // esconder testes
            if (skipTeste && p.dataset.puloBloco === "true") {
                p.style.display = "none";
                continue;
            }

            p.style.display = "block";

            if (!perguntaPreenchida(p)) {
                for (let j = i + 1; j < perguntas.length; j++)
                    perguntas[j].style.display = "none";

                botaoFinal.style.display = "none";
                return;
            }
        }

        // tudo certo → mostrar botão final
        botaoFinal.style.display = "block";
    }

    perguntas.forEach(p => {
        p.querySelectorAll("select, input").forEach(el => {
            el.addEventListener("input", avancarFluxo);
            el.addEventListener("change", avancarFluxo);
        });
    });

    avancarFluxo();
});
