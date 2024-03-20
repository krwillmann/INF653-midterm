<?php
class Database{
   
        private $conn;        
        private $host;
        //private $port;
        private $dbname;
        private $username;
        private $password;

        public function __construct(){
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
            $this->host = getenv('HOST');
            $this->dbname = getenv('DBNAME');
            //$this->port = getenv('PORT');
        }

        public function connect(){
            if($this->conn){
                //connection already exits, return it
                return $this->conn;
            }else{
                $dsn = "pgsql:host={$this->host};dbname={$this->dbname}";
                     //port={$this->port}
                try{
                    $this->conn = new PDO($dsn, $this->username, $this->password);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $this->conn;
                }catch(PDOException $e){
                    echo json_encode('Connection Error: ' . $e->getMessage());
                }
            }
        }
    }