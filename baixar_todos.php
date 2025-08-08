<?php
// Aumenta limites para lidar com muitos arquivos grandes
ini_set('memory_limit', '512M');         // Ajuste conforme necessário: 512M, 1G...
set_time_limit(300);                     // Tempo máximo de execução: 5 minutos

// Ativa exibição de erros para debug (remova em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['caminho'])) {
    http_response_code(400);
    echo "Caminho não especificado.";
    exit;
}

$caminhoRelativo = $_GET['caminho'];
$pastaBase = '/var/ping/dados/senarto/';

// Resolve o caminho real e impede acesso externo à base
$diretorio = realpath($pastaBase . $caminhoRelativo);
if (!$diretorio || strpos($diretorio, realpath($pastaBase)) !== 0) {
    http_response_code(403);
    echo "Acesso negado.";
    exit;
}

// Cria nome do ZIP e caminho temporário
$nomeZip = 'documentos_' . basename($caminhoRelativo) . '_' . uniqid() . '.zip';
$zipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $nomeZip;

// Inicia o ZIP
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    http_response_code(500);
    echo "Erro ao criar o arquivo ZIP.";
    exit;
}

// Adiciona os arquivos da pasta ao ZIP
$arquivos = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($diretorio, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($arquivos as $file) {
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($diretorio) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}

$zip->close();

// Inicia sessão para registrar log do usuário
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? 'desconhecido';
$nome = $_SESSION['nome'] ?? 'desconhecido';
$processo = basename($caminhoRelativo);

// Envia o ZIP ao navegador
if (file_exists($zipPath)) {
    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename=\"$nomeZip\"");
    header('Content-Length: ' . filesize($zipPath));

    ob_clean();     // Limpa qualquer saída anterior
    flush();        // Força o envio do cabeçalho
    readfile($zipPath);
    unlink($zipPath); // Remove o ZIP temporário
    exit;
} else {
    http_response_code(500);
    echo "Erro ao gerar o arquivo ZIP.";
    exit;
}
