<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['usuario_role'])) {
        setFlash('permissao_login', 'Faça login.', 'danger');
        header("Location: ?r=login");
        exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-Projeto - Sistema Usuário</title>
    <link rel="stylesheet" href="app/main/views/css/main.css">
</head>
<body>
    <div class="container home">
        <h1>Área do Usuário</h1>
        <p>Bem-vindo, <?php echo "<b>" . htmlspecialchars(explode(' ', trim($_SESSION['usuario_nome']))[0] ?? ''); ?> </b> — Role: <?php echo htmlspecialchars($_SESSION['usuario_role']); ?></p>
        <p><a href="?r=logout" class="btn btn-primary">Sair</a></p>
    </div>
</body>
</html>