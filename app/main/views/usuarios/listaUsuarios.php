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
    <title>Mini-Projeto - Lista Usuários</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="app/main/views/css/main.css">
    <style>
        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        .confirm-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 320px;
            font-family: sans-serif;
        }
        .confirm-message {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .confirm-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .confirm-buttons button {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-yes {
            background: #007bff;
            color: white;
        }

        .btn-yes:hover {
            background: #0056b3;
        }

        .btn-no {
            background: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <a href="?r=dashboard" class="btn btn-primary btn-back">Voltar</a>
    <div class="container user-list" style="max-width: 1000px;">
        <?php foreach (getAllFlashes() as $flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                    <i class="bi bi-x toggleFlash"></i>
                </div>
        <?php endforeach; ?>
        <h1 align="center">Lista de Usuários</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars(++$i); ?></td>
                        <td><?= htmlspecialchars($usuario['nome']); ?></td>
                        <td><?= htmlspecialchars($usuario['email']); ?></td>
                        <td><?= htmlspecialchars($usuario['role']); ?></td>
                        <td><?= htmlspecialchars($usuario['created_at'] ?? '-'); ?></td>
                        <td style="display: flex; gap: 8px; justify-content: center; text-align: center;">
                            <a href="?r=editar&id=<?= $usuario['id'] ?>" class="btn btn-primary">Editar</a>
                            <a href="?r=excluir&id=<?= $usuario['id'] ?>" class="btn btn-danger btn-excluir">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.querySelectorAll('.toggleFlash').forEach(toggle => {
            toggle.addEventListener('click', function() {
                this.closest('.alert').style.display = 'none';
            });
        });

        function customConfirm(message) {
            return new Promise((resolve) => {
                const overlay = document.createElement("div");
                overlay.className = "confirm-overlay";

                const box = document.createElement("div");
                box.className = "confirm-box";

                const msg = document.createElement("div");
                msg.className = "confirm-message";
                msg.textContent = message;

                const buttons = document.createElement("div");
                buttons.className = "confirm-buttons";

                const btnYes = document.createElement("button");
                btnYes.className = "btn-yes";
                btnYes.textContent = "Sim";

                const btnNo = document.createElement("button");
                btnNo.className = "btn-no";
                btnNo.textContent = "Não";

                buttons.appendChild(btnYes);
                buttons.appendChild(btnNo);
                box.appendChild(msg);
                box.appendChild(buttons);
                overlay.appendChild(box);
                document.body.appendChild(overlay);

                btnYes.onclick = () => {
                    document.body.removeChild(overlay);
                    resolve(true);
                };
                btnNo.onclick = () => {
                    document.body.removeChild(overlay);
                    resolve(false);
                };
            });
        }

        // Interceptar clique no botão Excluir
        document.querySelectorAll('.btn-excluir').forEach(link => {
            link.addEventListener('click', async function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                const resposta = await customConfirm("Deseja realmente excluir este usuário?");
                if (resposta) {
                    window.location.href = url;
                }
            });
        });
    </script>
</body>
</html>