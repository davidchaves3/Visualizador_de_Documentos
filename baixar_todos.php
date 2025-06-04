<?php
if (!isset($_GET['caminho'])) {
    http_response_code(400);
    echo "Caminho não especificado.";
    exit;
}

$caminhoRelativo = $_GET['caminho'];
$pastaBase = '/var/ping/dados/senarto/';
$diretorio = realpath($pastaBase . $caminhoRelativo);

if (!$diretorio || strpos($diretorio, realpath($pastaBase)) !== 0) {
    http_response_code(403);
    echo "Acesso negado.";
    exit;
}

$zipPath = sys_get_temp_dir() . '/arquivos_' . uniqid() . '.zip';
$zip = new ZipArchive();

if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
    echo "Não foi possível criar o arquivo ZIP.";
    exit;
}

$arquivos = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($diretorio),
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
// Registrar log (deixe isso antes dos headers)
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? 'desconhecido';
$nome = $_SESSION['nome'] ?? 'desconhecido';
$processo = basename($caminhoRelativo);

require_once "includes/log.php";
registrarLog("Download de todos os arquivos", $nome, $processo);

// Envia o ZIP para o navegador
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=arquivos_processo.zip');
header('Content-Length: ' . filesize($zipPath));
readfile($zipPath);
unlink($zipPath);
exit;
