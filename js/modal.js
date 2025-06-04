document.addEventListener("DOMContentLoaded", function() {
    console.log("Modal.js carregado!");

    var modal = document.getElementById("fileModal");
    var fileViewer = document.getElementById("fileViewer");
    var downloadBtn = document.getElementById("downloadBtn");
    var closeBtn = document.querySelector("#fileModal .close");

    var prevBtn = document.getElementById("prevBtn");
    var nextBtn = document.getElementById("nextBtn");

    var fileLinks = document.querySelectorAll(".file-link");
    var fileArray = [];
    var currentIndex = 0;

    
    modal.style.display = "none";

    // Preenche a lista de arquivos disponíveis
    fileLinks.forEach(function(link) {
        fileArray.push(link.getAttribute("data-file"));
    });

    //  REGISTRAR LOG DO DOWNLOAD
    if (downloadBtn) {
        downloadBtn.addEventListener("click", function(e) {
            e.preventDefault();

            var filePath = this.getAttribute("href");

            if (filePath) {
                let partesCaminho = filePath.split('/');
                let nomeProcesso = partesCaminho[4] || 'Desconhecido';

                fetch('includes/log.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'acao=' + encodeURIComponent('Download realizado') + 
                          '&caminho=' + encodeURIComponent(filePath) + 
                          '&processo=' + encodeURIComponent(nomeProcesso)
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Log de download registrado:', data);
                    var link = document.createElement("a");
                    link.href = filePath;
                    link.setAttribute("download", ""); 
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                })
                .catch(error => {
                    console.error('Erro ao registrar log de download:', error);
                });
            }
        });
    }

    // Evento ao clicar no documento
    fileLinks.forEach(function(link, index) {
        link.addEventListener("click", function(e) {
            e.preventDefault();

            var filePath = this.getAttribute("data-file");
            
            currentIndex = index;
            
            console.log("Abrindo modal para o arquivo:", filePath);

            if (filePath) {
                fileViewer.src = filePath + "#toolbar=0&navpanes=0&scrollbar=0";
                downloadBtn.href = filePath;
                modal.style.display = "flex";

                // REGISTRAR LOG DA VISUALIZAÇÃO
                let partesCaminho = filePath.split('/');
                let nomeProcesso = partesCaminho[4] || 'Desconhecido';

                fetch('includes/log.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'acao=' + encodeURIComponent('Visualizou o arquivo') + 
                          '&caminho=' + encodeURIComponent(filePath) + 
                          '&processo=' + encodeURIComponent(nomeProcesso)
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Log registrado:', data);
                })
                .catch(error => {
                    console.error('Erro ao registrar log:', error);
                });
            }
        });
    });

    // Função para navegar entre arquivos
    function navigateFile(direction) {
        if (direction === "next" && currentIndex < fileArray.length - 1) {
            currentIndex++;
        } else if (direction === "prev" && currentIndex > 0) {
            currentIndex--;
        } else {
            return;
        }

        var newFilePath = fileArray[currentIndex];
        console.log("Navegando para:", newFilePath);

        fileViewer.src = newFilePath + "#toolbar=0&navpanes=0&scrollbar=0";
        downloadBtn.href = newFilePath;
        let partesCaminho = newFilePath.split('/');
        let nomeProcesso = partesCaminho[4] || 'Desconhecido';

        fetch('includes/log.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'acao=' + encodeURIComponent('Visualizou o arquivo') +
                  '&caminho=' + encodeURIComponent(newFilePath) +
                  '&processo=' + encodeURIComponent(nomeProcesso)
        })
        .then(response => response.text())
        .then(data => {
            console.log('Log registrado ao navegar:', data);
        })
        .catch(error => {
            console.error('Erro no registro do log ao navegar:', error);
        });
    }

    prevBtn.addEventListener("click", function() {
        navigateFile("prev");
    });

    nextBtn.addEventListener("click", function() {
        navigateFile("next");
    });

    // Fecha o modal ao clicar no botão de fechar (X)
    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
        fileViewer.src = "";
    });

    // Fecha o modal ao clicar fora dele
    window.addEventListener("click", function(e) {
        if (e.target == modal) {
            modal.style.display = "none";
            fileViewer.src = "";
        }
    });
});
function abrirModal(filePath) {
    var modal = document.getElementById("fileModal");
    var fileViewer = document.getElementById("fileViewer");
    var downloadBtn = document.getElementById("downloadBtn");

    console.log("Abrindo modal para o arquivo:", filePath);

    if (filePath) {
        fileViewer.src = filePath + "#toolbar=0&navpanes=0&scrollbar=0";
        downloadBtn.href = filePath;
        modal.style.display = "flex";
    }else{
        console.error("Caminho do arquivo inválido!")
    }

    // Registra o log do arquivo acessado
    fetch('includes/log.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'acao=' + encodeURIComponent('Visualizou o arquivo') +
              '&caminho=' + encodeURIComponent(filePath) +
              '&processo=' + encodeURIComponent(filePath.split('/')[4] || 'Desconhecido')
    })
    .then(response => response.text())
    .then(data => {
        console.log('Log registrado:', data);
    })
    .catch(error => {
        console.error('Erro ao registrar log:', error);
    });
}
