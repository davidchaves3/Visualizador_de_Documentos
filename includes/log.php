<?php
include __DIR__ . "/../config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function registrarLog($usuario_id, $nome_usuario, $acao, $processos = '', $caminho = '') {
    global $pdo;

    if (empty($usuario_id) || empty($acao)) {
        return;
    }

    $stmt = $pdo->prepare("
        INSERT INTO logs (usuario_id, nome_usuario, acao, processos, caminho)
        VALUES (:usuario_id, :nome_usuario, :acao, :processos, :caminho)
    ");
    $stmt->execute([
        ':usuario_id'   => $usuario_id,
        ':nome_usuario' => $nome_usuario,
        ':acao'         => $acao,
        ':processos'    => $processos,
        ':caminho'      => $caminho
    ]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario_id   = $_SESSION['usuario_id'] ?? null;
    $nome_usuario = $_SESSION['nome'] ?? 'Usuário Desconhecido';
    $acao         = $_POST['acao'] ?? 'Ação não especificada';
    $caminho      = $_POST['caminho'] ?? '';
    $processos    = $_POST['processo'] ?? basename(dirname($caminho));

    registrarLog($usuario_id, $nome_usuario, $acao, $processos, $caminho);
}
