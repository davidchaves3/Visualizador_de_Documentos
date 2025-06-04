<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Portal de Arquivos - Bem-vindo</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="icon" href="img/icone.ico" type="image/x-icon">
</head>
<body>
  <div class="container">
    <img src="img/logo.png" alt="Logo" class="logo">
    <h1>Bem-vindo ao Portal de Arquivos</h1>
    <p>Visualize os arquivos do Ping Aqui</p>
    <a href="login.php" class="btn">Acessar Portal</a>
  </div>
</body>
</html>