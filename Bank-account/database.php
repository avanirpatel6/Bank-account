<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class DB { 
    private $SQL;
    private $host;
    private $user;
    private $password;
    private $dbName;
    public function __construct() {
        $this->host = "sql1.njit.edu";
        $this->user = "arp72";
        $this->password = "sloven46";
        $this->dbName = "arp72";
    
        $this->connectDB();
    }
  
    private function connectDB() {
        $dsn = "mysql:host=".$this->host.";dbname=".$this->dbName.";charset=utf8";
        $this->db = new PDO($dsn, $this->user, $this->password);
        echo "Connected to MySQL <br><br>";
    }
  
    private function fetchAll() {
        return $this->sqlQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query($SQL) {
        $this->sqlQuery = $this->db->query($SQL);
        return $this->fetchAll();
    }

    public function execute($SQL){
        return $this->sqlQuery = $this->db->exec($SQL);
    }
    public function getLastInsertId(){
        return $this->db->lastInsertId();
    } 
}



