<?php
include "../includes/admin_auth.php";  
include "../includes/admin_nav.php"; 
include "../includes/db.php";
include "../includes/log.php";


// formulário de criação de novo usuário 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'create') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $departamento = $_POST['departamento'];
    $mesas = $_POST['mesas'];
    
    // Gera o hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Insere o novo usuário na tabela (definindo admin = 0 para usuários comuns)
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, departamento, mesas, admin) VALUES (:nome, :email, :senha, :departamento, :mesas, 0)");
    $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'senha' => $senha_hash,
        'departamento' => $departamento,
        'mesas' => $mesas
    ]);
    
    // Registra log da atividade do administrador
    $admin_id = $_SESSION['usuario_id']; 
    registrarLog($_SESSION['usuario_id'], $_SESSION['nome'], "Criou o usuário: $nome", '', '');
    
    $mensagem = "Usuário criado com sucesso!";
}

// Recupera todos os usuários para exibição
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY criado_em DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Usuários - Administração</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/admin_nav.css">
    <!--<link rel="stylesheet" href="../css/delete_modal.css">-->
    <link rel="icon" href="../img/icone.ico" type="image/x-icon">
</head>
<body>
    <h1>Gestão de Usuários</h1>
    
    <?php if(isset($mensagem)) { echo "<p style='color:green;'>$mensagem</p>"; } ?>
    
    <h2>Cadastrar Novo Usuário</h2>
    <form method="POST">
        <input type="hidden" name="action" value="create">
        <label>Nome Completo:</label><br>
        <input type="text" name="nome" required><br><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>
        
        <label>Departamento:</label><br>
        <input type="text" name="departamento" required><br><br>
        
        <label>Mesas (separadas por vírgula):</label><br>
        <input type="text" name="mesas" placeholder="Ex: Mesa1, Mesa2"><br><br>
        
        <button type="submit">Cadastrar Usuário</button>
    </form>
    <h2>Lista de Usuários</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Departamento</th>
            <th>Mesas</th>
            <th>Ações</th>
        </tr>
        <?php foreach($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['id']) ?></td>
            <td><?= htmlspecialchars($usuario['nome']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td><?= htmlspecialchars($usuario['departamento']) ?></td>
            <td><?= htmlspecialchars($usuario['mesas']) ?></td>
            <td><a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn-edit">Editar</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
