<?php 

require_once "config.php";

class Database {
    private $username;
    private $password;
    private $dbName;
    private $host;
    private $port;


    public function __construct() {
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->dbName = DB_NAME;
        $this->host = DB_HOST;
        $this->port = DB_PORT;
    }


    public function connect() {
        try {
            $connection = new PDO(
                "pgsql:host=$this->host;port=$this->port;dbname=$this->dbName",
                $this->username,
                $this->password
            );

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

            return $connection;
        }catch(PDOException $err) {
            die("DB connection failed: " . $err->getMessage());
        }
    }
}