<?php
    session_start();

    $route = $_GET['r'] ?? 'home';

    switch ($route) {
        case 'cadastro':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/views/cadastro.php';
            break;
        
        case 'salva_cadastro':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/controllers/usuarioController.php';
            $controller = new usuarioController();
            $controller->Cadastro();
            break;

        case 'login':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/views/login.php';
            break;


        case 'fazer_login':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/controllers/usuarioController.php';
            $controller = new usuarioController();
            $controller->Login();
            break;

        case 'dashboard':
            // Sistema do Administrador (apenas admin)
            require __DIR__ . '/app/main/helpers/mensagens.php';
            if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] !== 'admin') {
                setFlash('permissao_acesso', 'Acesso negado.', 'danger');
                header("Location: ?r=login");
                exit;
            }
            require __DIR__ . '/app/main/views/admin/dashboard.php';
            break;

        case 'criar_usuario':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/views/usuarios/novoUsuario.php';
            break;

        case 'salvar_usuario':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/controllers/usuarioController.php';
            $controller = new usuarioController();
            $controller->Criar();

        case 'usuario_home':
            if (!isset($_SESSION['usuario_role'])) {
                setFlash('permissao_login', 'Faça login.', 'danger');
                header("Location: ?r=login");
                exit;
            }
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/views/usuario/home.php';
            break;

        case 'usuarios':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/controllers/usuarioController.php';
            $controller = new usuarioController();
            $controller->Listar();
            break;

        case 'editar':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/controllers/usuarioController.php';
            $controller = new usuarioController();
            $controller->Editar();
            break;

        case 'salva_editar':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/controllers/usuarioController.php';
            $controller = new usuarioController();
            $controller->Atualizar();
            break;
        
        case 'excluir':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/controllers/usuarioController.php';
            $controller = new usuarioController();
            $controller->Excluir();
            break;

        case 'logout':
            require __DIR__ . '/app/main/helpers/mensagens.php';
            require __DIR__ . '/app/main/controllers/usuarioController.php';
            $controller = new usuarioController();
            $controller->Logout();
            break;
        
        case 'home':
        default:
            echo "<!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Mini-Projeto - Home</title>
            <link rel='stylesheet' href='app/main/views/css/main.css'>
        </head>
        <body>
            <div class='container'>
                <h1>Sistema de Cadastro / Login</h1> 
                <a href='?r=cadastro' class='btn btn-primary'>Fazer Cadastro</a> 
                <a href='?r=login' class='btn btn-primary'>Fazer Login</a>    
            </div>
            </body>
            </html>";
            break;
        }
?>