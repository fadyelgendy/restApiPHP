<?php
class Database {
    // database credentials
    private $host = "localhost";
    private $db_name= "api_db";
    private $username = "root";
    private $password = "root";
    public $conn;

    //get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=".$this->host.";db_name=".$this->db_name.";", $this->username, $this->password);
        } catch(PDOException $e){
            die("ERROR: Connection Failed!. " . $e->getMessage());
        }

        return $this->conn;
    }
}