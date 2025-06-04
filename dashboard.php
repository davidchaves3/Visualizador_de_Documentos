<?php
include "config.php";
include "includes/log.php";


if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
if (!isset($_SESSION['nome'])) {
    $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $usuario_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['nome'] = $user ? $user['nome'] : "Usuário Desconhecido";
}

$nome_usuario = $_SESSION['nome']; 
$departamento = $_SESSION['departamento'];

function listarArquivos($pasta, $processo_nome) {
    //global $usuario_id, $nome_usuario;

    if (!is_dir($pasta)) {
        echo "<p>Nenhum documento disponível.</p>";
        return;
    }

    $arquivos = scandir($pasta);
    echo "<ul>";
    foreach ($arquivos as $arquivo) {
        if ($arquivo != "." && $arquivo != "..") {
            $caminho = $pasta . "/" . $arquivo;
            
            if (is_dir($caminho)) {
                echo "<li><details><summary>" . htmlspecialchars($arquivo) . "</summary>";
                listarArquivos($caminho, $processo_nome);
                echo "</details></li>";
            } else {
                $url = str_replace('/var/ping/dados/senarto/', '/arquivos/', $caminho);
                echo "<li>
                    <a href='#' class='file-link' data-file='" . htmlspecialchars($url) . "'>" . htmlspecialchars($arquivo) . "</a>
                </li>";
            }
        }
    }
    echo "</ul>";
}
function listarProcessos($pasta) {
    //global $usuario_id, $nome_usuario;

    if (!is_dir($pasta)) {
        echo "<p>Nenhum processo disponível.</p>";
        return;
    }

    $processos = scandir($pasta);
    echo "<ul>";
    foreach ($processos as $processo) {
        if ($processo != "." && $processo != "..") {
            $caminhoProcesso = $pasta . "/" . $processo;
            
            if (is_dir($caminhoProcesso)) {
                echo "<li class='processo-item'><details><summary>" . htmlspecialchars($processo) . "</summary>";
                listarArquivos($caminhoProcesso, $processo);
                echo "</details></li>";
            }
        }
    }
    echo "</ul>";
}

// Recupera as mesas atribuídas ao usuário
$usuario_id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare("SELECT mesas FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $usuario_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Processos por Mesa</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/dashboard.css?v=<?= time() ?>">
    <link rel="icon" href="img/icone.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/modal.css?v=<?= time() ?>">
</head>
<body>
    <h1>Bem-vindo ao Portal de Arquivos</h1>
    <!-- Menu de Navegação -->
    <nav>
        <ul>
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                <li><a href="admin/usuarios.php">Administração</a></li>
                <li><a href="admin/logs.php">Logs de Atividades</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
    <section>
    <h2>Processos Atribuídos</h2>
    <!-- Barra de Buscar processo -->
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Buscar processos..." onkeyup="buscarProcesso()">
        <div id="resultadosBusca"></div>
    </div>
        <?php
            if ($user && !empty($user['mesas'])) {
                    $mesasArray = array_map('trim', explode(',', $user['mesas']));
                    foreach ($mesasArray as $mesa) {
                        // Exibe um cabeçalho para cada mesa
                        echo "<h3>Mesa: " . htmlspecialchars($mesa) . "</h3>";
                        // Define o caminho: uploads/{mesa}
                        $pastaMesa = "/var/ping/dados/senarto/" . $mesa;
                        listarProcessos($pastaMesa);
            }
            } else {
                     echo "<p>Nenhuma mesa configurada para o usuário.</p>";
            }
        ?>
    </section>

<!-- Modal para Visualizar Arquivos -->
<div id="fileModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <iframe id="fileViewer" src="" frameborder="0"></iframe>
    <div class="modal-actions">
      <button id="prevBtn" class="btn-nav">← Documento Anterior</button>
      <a id="downloadBtn" class="btn-download" href="#" download>Download</a>
      <button id="nextBtn" class="btn-nav">Próximo Documento →</button>
    </div>
  </div>
</div>

<!-- Modal para Listagem de arquivos -->
<div id="modalListagem" class="modal">
  <div class="modal-content">
    <span class="close" id="closeModalListagem">&times;</span>
    <h3>Arquivos do Processo</h3>
    
    <div id="fileListagemContainer" class="file-list"></div>

    <div class="modal-actions">
      <button id="baixarTodosBtn" class="btn-download">Baixar Todos</button>
    </div>
  </div>
</div>

<script src="js/modal.js?v=<?= time() ?>"></script>
<script src="js/modalListagem.js?v=<?= time() ?>"></script>

<script>
function buscarProcesso() {
    var input = document.getElementById("searchInput").value.toLowerCase();
    var resultadosDiv = document.getElementById("resultadosBusca");

    if (input.length === 0) {
        resultadosDiv.innerHTML = "";
        return;
    }

    fetch("buscar_processos.php?q=" + encodeURIComponent(input))
        .then(response => response.json())
        .then(data => {
            resultadosDiv.innerHTML = "";
            if (data.length === 0) {
                resultadosDiv.innerHTML = "<p>Nenhum processo encontrado.</p>";
            } else {
                var lista = document.createElement("ul");
                data.forEach(function(processo) {
                    var item = document.createElement("li");
                    var link = document.createElement("a");
                    
                    link.href = "#";
                    link.classList.add("file-link");
                    link.setAttribute("data-file", processo.caminho);
                    link.textContent = processo.nome;

                    // Adiciona evento de clique para abrir no modal
                    link.addEventListener("click", function(e) {
                        e.preventDefault();
                        abrirModalListagem(processo.caminho);
                    });

                    item.appendChild(link);
                    lista.appendChild(item);
                });
                resultadosDiv.appendChild(lista);

            }
        })
        .catch(error => console.error("Erro na busca:", error));
}

document.addEventListener("click", function(event) {
    const resultadosBusca = document.getElementById("resultadosBusca");
    const searchInput = document.getElementById("searchInput");

    if (!resultadosBusca.contains(event.target) && !searchInput.contains(event.target)) {
        resultadosBusca.innerHTML = "";
    }
});

</script>

</body>
</html>
