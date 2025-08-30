<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-Projeto - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="app/main/views/css/main.css">
</head>
<body>
    <a href='index.php' class="btn btn-primary btn-back">Voltar</a>
    <div class="container">
        <h1>Login</h1>
        <form action="?r=fazer_login" method="POST">
            <?php foreach (getAllFlashes() as $flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                    <i class="bi bi-x toggleFlash"></i>
                </div>
            <?php endforeach; ?>
            Email: <input type="email" name="email">
            Senha: <input type="password" name="senha" id="senhaLogin">
            <div class="input-container">
                <i class="bi bi-eye" id="toggleLogin"></i>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
    <script>
        const toggleLogin = document.getElementById("toggleLogin");
        const senhaLogin = document.getElementById("senhaLogin");
        toggleLogin.addEventListener("click", () => {
            if (senhaLogin.type === "password") {
                senhaLogin.type = "text";
                toggleLogin.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                senhaLogin.type = "password";
                toggleLogin.classList.replace("bi-eye-slash", "bi-eye");
            }
        });

        document.querySelectorAll('.toggleFlash').forEach(toggle => {
            toggle.addEventListener('click', function() {
                this.closest('.alert').style.display = 'none';
            });
        });
    </script>
</body>
</html>