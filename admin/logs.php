<?php
include "../includes/db.php";
include "../includes/admin_auth.php";
include "../includes/log.php";

// Definir número de logs por página
$logs_por_pagina = 10;

// Verifica a página atual e calcula o OFFSET
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_atual < 1) $pagina_atual = 1;
$inicio = ($pagina_atual - 1) * $logs_por_pagina;

// Contar total de registros no banco de dados
$total_logs_stmt = $pdo->query("SELECT COUNT(*) FROM logs");
$total_logs = $total_logs_stmt->fetchColumn();
$total_paginas = ceil($total_logs / $logs_por_pagina);

// Buscar registros da página atual
$stmt = $pdo->prepare("SELECT * FROM logs ORDER BY data DESC LIMIT :inicio, :logs_por_pagina");
$stmt->bindValue(":inicio", $inicio, PDO::PARAM_INT);
$stmt->bindValue(":logs_por_pagina", $logs_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT logs.*, usuarios.nome FROM logs JOIN usuarios ON logs.usuario_id = usuarios.id ORDER BY logs.data DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs de Atividades</title>
    <link rel="stylesheet" href="../css/logs.css">
    <link rel="icon" href="../img/icone.ico" type="image/x-icon">
</head>
<body>
    <nav class="logs-menu">
        <ul>
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="../admin/usuarios.php">Usuários</a></li>
            <li class="active">Logs de Atividades</li>
        </ul>
    </nav>
    <h1>Registro de Atividades</h1>

    <div class="logs-container">
        <table class="logs-table">
            <thead>
            <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Ação</th>
            <th>Data</th>
            <th>Processo</th>
            <th>Documento Acessado</th>
        </tr>
            </thead>
            <tbody>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= htmlspecialchars($log['id']) ?></td>
            <td><?= htmlspecialchars($log['nome_usuario']) ?></td>
            <td><?= htmlspecialchars($log['acao']) ?></td>
            <td><?= htmlspecialchars($log['data']) ?></td>
            <td><?= htmlspecialchars($log['processos']) ?></td>
            <td><?= htmlspecialchars(basename($log['caminho'])) ?></td> 
        </tr>
        <?php endforeach; ?>
    </tbody>
        </table>

    <div class="pagination">
        <?php if ($pagina_atual > 1): ?>
            <a href="?pagina=<?= $pagina_atual - 1 ?>" class="page-link">&laquo; Anterior</a>
        <?php endif; ?>

        <?php
        $max_links = 5;
        $start = max(1, $pagina_atual - 2);
        $end = min($total_paginas, $pagina_atual + 2);

        if ($start > 1) echo '<span class="page-link">...</span>';

        for ($i = $start; $i <= $end; $i++): ?>
            <a href="?pagina=<?= $i ?>" class="page-link <?= ($i == $pagina_atual) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($end < $total_paginas) echo '<span class="page-link">...</span>'; ?>

        <?php if ($pagina_atual < $total_paginas): ?>
            <a href="?pagina=<?= $pagina_atual + 1 ?>" class="page-link">Próximo &raquo;</a>
        <?php endif; ?>
    </div>

    <a href="../dashboard.php" class="back-button">Voltar</a>

</body>
</html>