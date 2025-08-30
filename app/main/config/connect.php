<?php 
    class Banco {
        private $host = "localhost";
        private $dbname = "projeto3";
        private $user = "root";
        private $pass = "";
        public $conn;

        public function Conectar() {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass,[
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                return $this->conn;
            } catch (PDOException $e) {
                // echo "Erro de conexão " . $e->getMessage();
                throw new RuntimeException('Falha de conexão ao banco.');
            }
        }
    }
?>