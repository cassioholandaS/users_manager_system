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
    <title>Mini-Projeto - Editar Usuário</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="app/main/views/css/main.css">
</head>
<body>
    <a href='?r=usuarios' class="btn btn-primary btn-back">Voltar</a>
    <div class="container">
        <h1>Editar perfil de <?= htmlspecialchars(explode(' ', trim($usuario['nome']))[0] ?? '') ?></h1>
        <form action="?r=salva_editar&id=<?= urlencode($usuario['id'])?>" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id'] ?? '') ?>">
            Nome: <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>">
            Email: <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">
            Senha: <input type="text" name="senha" id="senhaEditar" placeholder="Digite a nova senha (Opcional)">
            <div class="input-container">
                <i class="bi bi-eye" id="toggleEditar"></i>
            </div>
            Role: <select name="role" required>
                    <option value="usuario" <?= ($usuario['role'] ?? '') === 'usuario' ? 'selected' : '' ?>>Usuário</option>
                    <option value="admin" <?= ($usuario['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                </select>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
        <script>
            const toggleEditar = document.getElementById("toggleEditar");
            const senhaEditar = document.getElementById("senhaEditar");
            toggleEditar.addEventListener("click", () => {
                if (senhaEditar.type === "password") {
                    senhaEditar.type = "text";
                    toggleEditar.classList.replace("bi-eye", "bi-eye-slash");
                } else {
                    senhaEditar.type = "password";
                    toggleEditar.classList.replace("bi-eye-slash", "bi-eye");
                }
            });
            
            document.querySelectorAll('.toggleFlash').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    this.closest('.alert').style.display = 'none';
                });
            });
        </script>
    </div>
</body>