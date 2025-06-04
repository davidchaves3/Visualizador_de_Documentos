<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <link rel="stylesheet" href="css/logout.css?v=<?= time() ?>">
    <link rel="icon" href="img/icone.ico" type="image/x-icon">
</head>
<body>
    <img src="img/logo.png" alt="Logo" class="logo">
    <div class="logout-container">
        <h2>Você saiu do sistema</h2>
        <p>Obrigado por utilizar o portal. Esperamos vê-lo em breve.</p>
        <a href="login.php">
            <button>Voltar para o Login</button>
        </a>
    </div>
</body>
</html>
