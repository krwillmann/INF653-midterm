<?php
class Database{
    /*private $host = 'localhost';
    private $port = '5432';
    private $db_name = 'quotesdb';
    private $username = 'postgres';
    private $password = 'postgres';
    private $conn;

    public function connect() {
        $this->conn = null;
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }
}*/
        private $conn;
        private $host;
        private $port;
        private $dbname;
        private $username;
        private $password;

        public function __construct(){
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
            $this->dbname = getenv('DBNAME');
            $this->host = getenv('HOST');
            $this->port = getenv('PORT');
        }

        public function connect(){
            if($this->conn){
                //connection already exits, return it
                return $this->conn;
            }else{
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};username={$this->username};password={$this->password};";
                        
                try{
                    $this->conn = new PDO($dsn, $this->username, $this->password);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $this->conn;
                }catch(PDOException $e){
                    echo 'Connection Error: ' . $e->getMessage();
                }
            }
        }
    }

