document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("modalListagem");
    if (modal) {
        modal.style.display = "none";
    }
});

function abrirModalListagem(filePath) {
    const modalListagem = document.getElementById("modalListagem");
    const fileListContainer = document.getElementById("fileListagemContainer");
    const baixarTodosBtn = document.getElementById("baixarTodosBtn");

    if (!modalListagem || !fileListContainer) {
        console.error("Elementos do modal de listagem não encontrados.");
        return;
    }

    modalListagem.style.display = "flex";
    fileListContainer.innerHTML = "<p>Carregando arquivos...</p>";

    const caminhoProcesso = filePath.split("/").slice(2, -1).join("/");

    fetch("listar_arquivos_processo.php?caminho=" + encodeURIComponent(caminhoProcesso))
        .then(res => res.json())
        .then(arquivos => {
            fileListContainer.innerHTML = "";

            if (!arquivos || arquivos.length === 0) {
                fileListContainer.innerHTML = "<p style='color: red;'>Nenhum arquivo encontrado.</p>";
                return;
            }

            arquivos.forEach(arquivo => {
                const item = document.createElement("div");
                item.classList.add("file-card");
                item.innerHTML = `
                    <span class="file-name">${arquivo.nome}</span>
                    <button class="btn-nav" onclick="fecharModalListagem(); abrirModal('${arquivo.url}')">Visualizar</button>
                    `;
                fileListContainer.appendChild(item);
            });
        })
        .catch(err => {
            console.error("Erro ao buscar arquivos do processo:", err);
            fileListContainer.innerHTML = "<p style='color: red;'>Erro ao carregar arquivos.</p>";
        });

    const closeBtn = document.getElementById("closeModalListagem");
    if (closeBtn) {
        closeBtn.onclick = () => {
            modalListagem.style.display = "none";
            fileListContainer.innerHTML = "";
        };
    }

    if (!window._modalListagemClickBound) {
        window.addEventListener("click", function(event) {
            const modalListagem = document.getElementById("modalListagem");
            if (modalListagem && event.target === modalListagem) {
                fecharModalListagem();
            }
        });
        window._modalListagemClickBound = true;
    }
    

    if (baixarTodosBtn) {
        baixarTodosBtn.onclick = () => {
            const caminhoProcesso = filePath.split("/").slice(2, -1).join("/");
            const urlDownload = "baixar_todos.php?caminho=" + encodeURIComponent(caminhoProcesso);
            window.open(urlDownload, "_blank");
        };
    }    
}

function fecharModalListagem() {
    const modal = document.getElementById("modalListagem");
    const fileListContainer = document.getElementById("fileListagemContainer");
    const baixarTodosBtn = document.getElementById("baixarTodosBtn");

    if (modal) modal.style.display = "none";
    if (fileListContainer) fileListContainer.innerHTML = "";
    if (baixarTodosBtn) {
        baixarTodosBtn.onclick = null;
    }
}


