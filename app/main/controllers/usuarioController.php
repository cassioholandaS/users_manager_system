<?php 
    require_once __DIR__ . '/../helpers/mensagens.php';
    require_once __DIR__ . '/../models/usuario.php';

    class usuarioController {

        public function Cadastro(): void {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header("Location: ?r=cadastro");
                exit;
            }

            $nome = trim($_POST['nome']) ?? '';
            $email = trim($_POST['email']) ?? '';
            $senha = $_POST['senha'] ?? '';

            if ($nome === '' || $email === '' || $senha === '') {
                setFlash('cadastro_campos_vazios', 'Preencha todos os campos.', 'danger');
                header("Location: ?r=cadastro");
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                setFlash('cadastro_email_invalido', 'Email inválido.', 'danger');
                header("Location: ?r=cadastro");
                exit;
            }

            if (mb_strlen($senha) < 6) {
                setFlash('cadastro_senha_pequena', 'A senha deve ter no mínimo 6 caracteres.', 'danger');
                header("Location: ?r=cadastro");
                exit;
            }

            try {
                $usuario = new Usuario();
                if ($usuario->verificarUsuario($nome, $email)) {
                    setFlash('cadastro_existe', 'Usuário já cadastrado.', 'danger');
                    header("Location: ?r=cadastro");
                    exit;
                }

                $usuario->cadastrarUsuario($nome, $email, $senha);
                setFlash('cadastro_sucesso', 'Usuário cadastrado com sucesso.',  'success');
                header("Location: ?r=cadastro");
                exit;

            } catch (PDOException $e) {
                setFlash('cadastro_erro', 'Erro ao cadastrar Usuário.', 'danger');
                header("Location: ?r=cadastro");
                exit;
            }
        }

        public function Login(): void {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header("Location: ?r=login");
                exit;
            }

            $email = trim($_POST['email']) ?? '';
            $senha = trim($_POST['senha']) ?? '';

            if ($email === '' || $senha === '') {
                setFlash('login_campos_vazios', 'Preencha todos os campos.', 'danger');
                header("Location: ?r=login");
                exit;
            }

            try {
                $usuario = new Usuario();
                $row = $usuario->buscarUsuarioPorEmail($email);
                $senhaHash = is_array($row) && isset($row['senha']) ? trim($row['senha']) : '';
                
                // Debug Temporário
                // var_dump($row); 
                // var_dump($senhaHash); 
                // var_dump(password_verify($senha, $senhaHash)); 
                // exit;

                if (empty($row)) {
                    setFlash('cadastro_inexistente', 'Usuário não existe.', 'danger');
                    header("Location: ?r=login");
                    exit;
                }

                if (!$row || !password_verify($senha, $senhaHash)) {
                    setFlash('login_incorreto', 'E-mail ou senha incorretos.', 'danger');
                    header("Location: ?r=login");
                    exit;
                }

                
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = (int)$row['id'];
                $_SESSION['usuario_nome'] = $row['nome'];
                $_SESSION['usuario_email'] = $row['email'];
                $_SESSION['usuario_role'] = $row['role'];
                // $_SESSION['user_senha'] = $row['senha'];

                if ($_SESSION['usuario_role'] === 'admin') {
                    // setFlash('login_sucesso_admin', 'Seja bem-vindo, ' . explode(' ', trim($_SESSION['usuario_nome'])), 'info');
                    header('Location: ?r=dashboard');
                    exit;
                } else {
                    // setFlash('login_sucesso_usuario', 'Seja bem-vindo, ' . explode(' ', trim($_SESSION['usuario_nome'])), 'info');
                    header('Location: ?r=usuario_home');
                    exit;
                }
            } catch (PDOException $e) {
                setFlash('login_erro', 'Erro ao fazer Login.', 'danger');
                header("Location: ?r=login");
                exit;
            }
        }

        public function Criar(): void {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header("Location: ?r=cadastro");
                exit;
            }
            
            $nome = trim($_POST['nome']) ?? '';
            $email = trim($_POST['email']) ?? '';
            $senha = $_POST['senha'] ?? '';

            if ($nome === '' || $email === '' || $senha === '') {
                setFlash('cadastro_campos_vazios', 'Preencha todos os campos.', 'danger');
                header("Location: ?r=criar_usuario");
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                setFlash('cadastro_email_invalido', 'Email inválido.', 'danger');
                header("Location: ?r=criar_usuario");
                exit;
            }

            if (mb_strlen($senha) < 6) {
                setFlash('cadastro_senha_pequena', 'A senha deve ter no mínimo 6 caracteres.', 'danger');
                header("Location: ?r=criar_usuario");
                exit;
            }

            try {
                $usuario = new Usuario();
                if ($usuario->verificarUsuario($nome, $email)) {
                    setFlash('cadastro_existe', 'Este usuário já existe.', 'danger');
                    header("Location: ?r=criar_usuario");
                    exit;
                }

                $usuario->criarUsuario($nome, $email, $senha);
                setFlash('cadastro_sucesso', 'Usuário criado com sucesso.',  'success');
                header("Location: ?r=dashboard");
                exit;

            } catch (PDOException $e) {
                setFlash('cadastro_erro', 'Erro ao criar Usuário.', 'danger');
                header("Location: ?r=dashboard");
                exit;
           }
        }

        public function Listar(): void {
            if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] !== 'admin') {
            setFlash('permissao_listar', 'Acesso negado.', 'danger');
            header("Location: ?r=login");
            exit;
            }

            try {
                $usuarioModel = new Usuario();
                $usuarios = $usuarioModel->listarUsuarios();

                require __DIR__ . '/../views/usuarios/listaUsuarios.php';
            } catch (PDOException $e) {
                setFlash('listar_erro', 'Erro ao listar Usuários.', 'danger');
                header("Location: ?r=dashboard");
                exit;
            }
        }

        public function Editar(): void {
            if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] !== 'admin') {
            setFlash('permissao_listar', 'Acesso negado.', 'danger');
            header("Location: ?r=login");
            exit;
            }
            
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                setFlash('editar_invalido', 'Usuário inválido.', 'danger');
                header("Location: ?r=usuarios");
                exit;
            }

            $id = (int) $_GET['id'];
            
            try {

                $usuarioModel = new Usuario();
                $dados_usuario = $usuarioModel->buscarUsuarioPorId($id);
                
                $usuario['id'] = $dados_usuario['id'] ?? '';
                $usuario['nome'] = $dados_usuario['nome'] ?? '';
                $usuario['email'] = $dados_usuario['email'] ?? '';
                $usuario['role'] = $dados_usuario['role'] ?? '';
                
                if (empty($dados_usuario)) {
                    setFlash('usuario_invalido', 'Usuário não encontrado', 'danger');
                    header("Location: ?r=usuarios");
                    exit;
                }
                
                require __DIR__ . '/../views/usuarios/editarUsuario.php';
            } catch (PDOException $e) {
                setFlash('editar_erro', 'Erro ao carregar dados do Usuário.', 'danger');
                header("Location: ?r=usuarios");
                exit;
            }
        }
        
        public function Atualizar(): void {
            if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] !== 'admin') {
            setFlash('permissao_listar', 'Acesso negado.', 'danger');
            header("Location: ?r=login");
            exit;
            }

            $id = (int) $_GET['id'];
            $usuario['nome'] = trim($_POST['nome']);
            $usuario['email'] = trim($_POST['email']);
            $usuario['senha'] = $_POST['senha'] ?? null;
            $usuario['role'] = $_POST['role'];

            if (empty($usuario['nome']) || empty($usuario['email']) || empty($usuario['role'])) {
                setFlash('editar_campos_vazios', 'Preencha todos os campos.', 'danger');
                header("Location: ?r=editar&id=" . $id);
                exit;
            }

            try {
                $usuarioModel = new Usuario();
                $usuarioModel->editarUsuario($id, $usuario['nome'], $usuario['email'], $usuario['senha'], $usuario['role']);
                
                setFlash('editar_sucesso', 'Usuário atualizado com sucesso.', 'success');
                header("Location: ?r=usuarios");
                exit;
            } catch (PDOException $e) {
                setFlash('editar_erro', 'Erro ao atualizar usuário.', 'danger');
                header("Location: ?r=usuarios");
                exit;
            }
        }

        public function Excluir() {
            if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] !== 'admin') {
            setFlash('permissao_listar', 'Acesso negado.', 'danger');
            header("Location: ?r=login");
            exit;
            }

            if (!isset($_GET['id']) || empty($_GET['id'])) {
                setFlash('deletar_invalido', 'Usuário inválido.', 'danger');
                header("Location: ?r=usuarios");
                exit;
            }

            $id = (int) $_GET['id'];

            try {
                $usuario = new Usuario();
                if ($usuario->excluirUsuario($id)) {
                    setFlash('deletar_sucesso', 'Usuário excluído com sucesso.', 'success');
                    header("Location: ?r=usuarios");
                    exit;
                }
            } catch (PDOException $e) {
                setFlash('deletar_erro', 'Erro ao excluir usuário.', 'danger');
                header("Location: ?r=usuarios");
                exit;
            }
        }

        public function Logout(): void {
            session_unset();
            session_destroy();
            header('Location: ?r=home');
            exit;
        }
    }

?>
