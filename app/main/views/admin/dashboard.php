<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] !== 'admin') {
        setFlash('permissao_acesso', 'Acesso negado.', 'danger');
        header("Location: ?r=login");
        exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-Projeto - Sistema Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="app/main/views/css/main.css">
</head>
<body>
    <a href="?r=logout" class="btn btn-primary btn-back">Sair</a>
    <div class="container dashboard">
        <?php foreach (getAllFlashes() as $flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                <?= htmlspecialchars($flash['message']) ?>
                <i class="bi bi-x toggleFlash"></i>
            </div>
        <?php endforeach; ?>
        <h1>Dashboard (Admin)</h1>
        <p>Bem-vindo, <?php echo "<b>" . htmlspecialchars(explode(' ', trim($_SESSION['usuario_nome']))[0] ?? ''); ?> </b> — Role: <?php echo htmlspecialchars($_SESSION['usuario_role']) ?? ''; ?></p><br>
        <ul>
            <a href="?r=usuarios" class="btn btn-primary">Gerenciar Usuários <i class="bi bi-person-fill-gear"></i></a>
            <a href="?r=criar_usuario" class="btn btn-primary">Criar Novo Usuário <i class="bi bi-person-fill-add"></i></a>
        </ul>
    </div>
    <script>
        document.querySelectorAll('.toggleFlash').forEach(toggle => {
            toggle.addEventListener('click', function() {
                this.closest('.alert').style.display = 'none';
            });
        });
    </script>
</body>
</html>
