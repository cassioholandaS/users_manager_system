<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-Projeto - Cadastro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="app/main/views/css/main.css">
</head>
<body>
    <a href='?r=home' class="btn btn-primary btn-back">Voltar</a>
    <div class="container">
        <h1>Cadastro</h1>
        <form action="?r=salva_cadastro" method="POST">
            <?php foreach (getAllFlashes() as $flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                    <i class="bi bi-x toggleFlash"></i>
                </div>
            <?php endforeach; ?>
                Nome: <input type="text" name="nome">
                Email: <input type="email" name="email">
                Senha: <input type="password" name="senha" id="senhaCadastro" minlength="6">
                <div class="input-container">
                    <i class="bi bi-eye" id="toggleCadastro"></i>
                </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <script>
            const toggleCadastro = document.getElementById("toggleCadastro");
            const senhaCadastro = document.getElementById("senhaCadastro");
            toggleCadastro.addEventListener("click", () => {
                if (senhaCadastro.type === "password") {
                    senhaCadastro.type = "text";
                    toggleCadastro.classList.replace("bi-eye", "bi-eye-slash");
                } else {
                    senhaCadastro.type = "password";
                    toggleCadastro.classList.replace("bi-eye-slash", "bi-eye");
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
</html>