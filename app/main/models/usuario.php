<?php 
    require_once __DIR__ . '/../config/connect.php';

    class Usuario {
        private $conn;
        
        public function __construct()
        {
            $db = new Banco();
            $this->conn = $db->Conectar();
        }

        public function cadastrarUsuario(string $nome, string $email, string $senha, string $role = 'usuario'): bool {
            $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, role) VALUES (:nome, :email, :senha, :role)");
            $stmt->bindValue(":nome", $nome);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":senha", password_hash($senha, PASSWORD_DEFAULT));
            $stmt->bindValue(":role", $role);
            return $stmt->execute();
        }

        public function buscarUsuarioPorEmail(string $email): ?array {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email  LIMIT 1");
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        public function buscarUsuarioPorId(int $id): ?array {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        
        public function verificarUsuario(string $nome, string $email): bool {
            $stmt = $this->conn->prepare("SELECT 1 FROM usuarios WHERE nome = :nome OR email = :email LIMIT 1");
            $stmt->bindValue(":nome", $nome);
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        }

        public function criarUsuario(string $nome, string $email, string $senha, string $role = 'usuario'): bool {
            $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, role) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $role]);
        }
        
        public function listarUsuarios(): array {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE role = ?");
            $stmt->execute(['usuario']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function editarUsuario(int $id, string $nome, string $email, ?string $senha, string $role = 'usuario'): void {
            if (!empty($senha)) {
                $stmt = $this->conn->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, role = :role WHERE id = :id");
                $stmt->bindValue(":senha", password_hash($senha, PASSWORD_DEFAULT));
            } else {
                $stmt = $this->conn->prepare("UPDATE usuarios SET nome = :nome, email = :email, role = :role WHERE id = :id");
            }
            $stmt->bindValue(":nome", $nome);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":role", $role);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        }


        public function excluirUsuario(int $id): bool {
            $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = :id");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
    }
?>