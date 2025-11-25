document.addEventListener("DOMContentLoaded", function () {

    const perguntas = Array.from(document.querySelectorAll(".pergunta"));
    const botaoFinal = document.getElementById("botaoFinal");

    // Mostra apenas a primeira no início
    perguntas.forEach((p, i) => p.style.display = i === 0 ? "block" : "none");

    // ---------- HELPERS ----------
    function findTestStartIndex() {
        return perguntas.findIndex(p => p.dataset.isTestBlock === "true");
    }

    function show(el) { el.style.display = "block"; }
    function hide(el) { el.style.display = "none"; }

    function validaMinMaxInline(perguntaDiv) {
        const minInput = perguntaDiv.querySelector("input[name$='_min']");
        const maxInput = perguntaDiv.querySelector("input[name$='_max']");
        const msgEl = perguntaDiv.querySelector(".mensagem-erro");

        if (!minInput || !maxInput) {
            if (msgEl) msgEl.style.display = "none";
            return true;
        }

        if (minInput.value === "" || maxInput.value === "") {
<<<<<<< HEAD
            msgEl.style.display = "none";
=======
            if (msgEl) msgEl.style.display = "none";
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
            return false;
        }

        const nmin = Number(minInput.value);
        const nmax = Number(maxInput.value);

<<<<<<< HEAD
=======
        if (isNaN(nmin) || isNaN(nmax)) {
            msg("Digite apenas números válidos.");
            return false;
        }

>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
        if (nmin < 30 || nmin > 70 || nmax < 30 || nmax > 70) {
            msg("Valores válidos: 30 a 70.");
            return false;
        }

        if (nmax < nmin) {
            msg("O máximo não pode ser menor que o mínimo.");
            return false;
        }

        msgEl.style.display = "none";
        return true;

        function msg(t) {
            msgEl.textContent = t;
            msgEl.style.display = "block";
        }
    }

    function perguntaEstaPreenchida(perguntaDiv) {
        const sel = perguntaDiv.querySelector("select");
        if (sel && sel.value === "") return false;

        const text = perguntaDiv.querySelector("input[type='text']");
        if (text && text.value.trim() === "") return false;

        const min = perguntaDiv.querySelector("input[name$='_min']");
        const max = perguntaDiv.querySelector("input[name$='_max']");
<<<<<<< HEAD
        if (min && max) return validaMinMaxInline(perguntaDiv);
=======
        if (min || max) return validaMinMaxInline(perguntaDiv);
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597

        const numbers = perguntaDiv.querySelectorAll("input[type='number']");
        for (let n of numbers) {
            if (n.value.trim() === "") return false;
        }

        return true;
    }

<<<<<<< HEAD
    // ---------- LÓGICA PRINCIPAL ----------
=======
    // ---------- LÓGICA PRINCIPAL DE FLUXO ----------
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
    function avancarFluxo() {
        const testStart = findTestStartIndex();
        let skipMontagem = false;
        let skipTeste = false;

<<<<<<< HEAD
        // Detectando skip da fase de montagem
        perguntas.forEach((p, i) => {
            const sel = p.querySelector("select");
            if (!sel) return;

            if (p.dataset.skip === "true" && sel.value.toLowerCase() === "nao") {
                if (testStart === -1 || i < testStart) {
                    skipMontagem = true;
                }
            }
        });

        // Detecta se Teste Aplicável = NÃO
        if (testStart !== -1) {
            const pTesteAplicavel = perguntas[testStart];
            const sel = pTesteAplicavel.querySelector("select");
            if (sel && sel.value.toLowerCase() === "nao") skipTeste = true;
        }

        // Fluxo exibindo/ocultando perguntas
        for (let i = 0; i < perguntas.length; i++) {
            const p = perguntas[i];

            // Oculta montagem
            if (skipMontagem && i > 0 && (testStart !== -1 && i < testStart)) {
=======
        // Regra 1: Se pergunta de montagem com skip_if_no === "true" tiver "não", pulamos montagem.
        perguntas.forEach((p, i) => {
            const sel = p.querySelector("select");
            if (!sel) return;
            const val = sel.value.toLowerCase();

            if (p.dataset.skip === "true" && val === "nao") {
                if (testStart === -1 || i < testStart)
                    skipMontagem = true;
            }
        });

        // Regra 2: Detectar se o teste aplicável foi marcado como NÃO
        if (testStart !== -1) {
            const pTeste = perguntas[testStart];
            const sel = pTeste.querySelector("select");
            if (sel && sel.value.toLowerCase() === "nao") {
                skipTeste = true;
            }
        }

        // Fluxo de exibição
        for (let i = 0; i < perguntas.length; i++) {
            const p = perguntas[i];
            const isTestBlock = p.dataset.isTestBlock === "true";
            const puloBloco = p.dataset.puloBloco === "true";

            // --- Regra: ocultar montagem caso skipMontagem seja TRUE ---
            if (skipMontagem && (i > 0 && i < testStart)) {
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
                hide(p);
                continue;
            }

<<<<<<< HEAD
            // Oculta bloco de teste
            if (skipTeste && p.dataset.puloBloco === "true") {
=======
            // --- Regra: ocultar bloco de teste se test_aplicavel = NÃO ---
            if (skipTeste && puloBloco) {
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
                hide(p);
                continue;
            }

            show(p);

<<<<<<< HEAD
            // Se pergunta atual não válida → oculta as próximas
=======
            // Se a pergunta atual não está preenchida, todas as próximas somem
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
            if (!perguntaEstaPreenchida(p)) {
                for (let j = i + 1; j < perguntas.length; j++) hide(perguntas[j]);
                botaoFinal.style.display = "none";
                return;
            }
        }

<<<<<<< HEAD
        botaoFinal.style.display = "block";
    }

    // Eventos
=======
        // Se todas estão válidas
        botaoFinal.style.display = "block";
    }

    // ---------- EVENTOS ----------
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
    perguntas.forEach(p => {
        p.querySelectorAll("select, input").forEach(el => {
            el.addEventListener("input", avancarFluxo);
            el.addEventListener("change", avancarFluxo);
        });
    });

    document.addEventListener("keydown", e => {
        if (e.key === "Enter") {
            const el = document.activeElement;
            if (["INPUT", "SELECT"].includes(el.tagName)) {
                e.preventDefault();
                avancarFluxo();
            }
        }
    });

<<<<<<< HEAD
=======
    // Foca na primeira
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
    perguntas[0].querySelector("select,input")?.focus();
});


<<<<<<< HEAD
// ---------- LIMITADOR DE RSSI ----------
=======
// ---------- FUNÇÃO PARA LIMITAR RSSI ----------
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
function validarRSSI(input) {
    let value = Number(input.value);
    if (value < 30) input.value = 30;
    if (value > 70) input.value = 70;

    const container = input.closest(".minmax-container");
    if (!container) return;

    const min = container.querySelector("input[name$='_min']");
<<<<<<< HEAD
    const max = container.querySelector("input[name$.'_max']");
=======
    const max = container.querySelector("input[name$='_max']");
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597

    if (Number(max.value) < Number(min.value)) {
        max.value = min.value;
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> ff0749ffd777fdbaab9ba40cdcd24e8a1014d597
