document.addEventListener("DOMContentLoaded", function () {

    const blocos = Array.from(document.querySelectorAll(".bloco"));
    const botaoFinal = document.getElementById("botaoFinal");

    if (!blocos.length) return;

    // Função para habilitar/desabilitar inputs (exceto select aplicável)
    function setDisabled(container, disabled) {
        container.querySelectorAll("input, select, textarea").forEach(el => {
            if (!el.classList.contains("aplicavel")) {
                el.disabled = !!disabled;
                if (disabled) el.classList.add("disabled-temporario");
                else el.classList.remove("disabled-temporario");
            }
        });
    }

    // Checa se todos os inputs do bloco estão preenchidos
    function blocoPreenchido(bloco) {
        const ativos = Array.from(
            bloco.querySelectorAll("input, select, textarea")
        ).filter(el => !el.disabled && !el.classList.contains("aplicavel"));

        for (let el of ativos) {
            if (el.tagName === "SELECT" && el.value === "") return false;
            if (el.type === "text" && el.value.trim() === "") return false;
            if (el.type === "number" && el.value === "") return false;
        }

        return true;
    }

    function atualizarFluxo() {

        let liberar = true;
        let algumAplicavel = false; // Indica se há pelo menos um bloco aplicável

        blocos.forEach(bloco => {

            const selectAplicavel = bloco.querySelector("select.aplicavel");

            if (selectAplicavel) {
                if (selectAplicavel.value.toLowerCase() === "nao") {
                    setDisabled(bloco, true);
                } else {
                    setDisabled(bloco, false);
                    algumAplicavel = true;
                }
            } else {
                // blocos sem pergunta de aplicável sempre contam
                algumAplicavel = true;
            }

            if (liberar && !blocoPreenchido(bloco) && selectAplicavel?.value.toLowerCase() !== "nao") {
                liberar = false;
            }
        });

        if (botaoFinal) {
            // Se não houver nenhum bloco aplicável, não mostra o botão
            botaoFinal.style.display = (liberar && algumAplicavel) ? "block" : "none";
        }
    }

    // Eventos de mudança
    blocos.forEach(bloco => {
        bloco.querySelectorAll("input, select, textarea").forEach(el => {
            el.addEventListener("input", atualizarFluxo);
            el.addEventListener("change", atualizarFluxo);
        });
    });

    atualizarFluxo();
});
