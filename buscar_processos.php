<?php
include "config.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare("SELECT mesas FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $usuario_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || empty($user['mesas'])) {
    echo json_encode([]);
    exit;
}

$mesas = array_map('trim', explode(',', $user['mesas']));
$query = isset($_GET['q']) ? strtolower($_GET['q']) : '';

$resultados = [];

foreach ($mesas as $mesa) {
    $pastaMesa = "/var/ping/dados/senarto/" . $mesa;
    
    if (is_dir($pastaMesa)) {
        $diretorios = scandir($pastaMesa);
        foreach ($diretorios as $situacao) {
            if ($situacao != "." && $situacao != "..") {
                $pastaSituacao = $pastaMesa . "/" . $situacao;
                if (is_dir($pastaSituacao)) {
                    $processos = scandir($pastaSituacao);
                    foreach ($processos as $processo) {
                        if ($processo != "." && $processo != "..") {
                            if (strpos(strtolower($processo), $query) !== false) {
                                $resultados[] = [
                                    'nome' => $processo,
                                    'caminho' => "/arquivos/" . $mesa . "/" . $situacao . "/" . $processo . "/" . $arquivo
                                ];
                            }
                        }
                    }
                }
            }
        }
    }
}

echo json_encode($resultados);
?>