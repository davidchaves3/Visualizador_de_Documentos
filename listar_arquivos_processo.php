<?php
header('Content-Type: application/json');

if (!isset($_GET['caminho'])) {
    http_response_code(400);
    echo json_encode(["erro" => "Caminho inválido."]);
    exit;
}

$pastaBase = '/var/ping/dados/senarto/';
$caminhoRelativo = $_GET['caminho'];
$diretorio = realpath($pastaBase . $caminhoRelativo);

// Verifica se o caminho existe e está dentro da pasta base
if (!$diretorio || strpos($diretorio, realpath($pastaBase)) !== 0) {
    http_response_code(403);
    echo json_encode(["erro" => "Acesso negado."]);
    exit;
}

// Lista os arquivos válidos
$arquivos = array_diff(scandir($diretorio), ['.', '..']);
$resultado = [];

foreach ($arquivos as $arquivo) {
    // Ignora arquivos desnecessários
    if ($arquivo === 'desktop.ini') continue;

    $resultado[] = [
        'nome' => $arquivo,
        'url' => '/arquivos/' . $caminhoRelativo . '/' . $arquivo
    ];
}

echo json_encode($resultado);
