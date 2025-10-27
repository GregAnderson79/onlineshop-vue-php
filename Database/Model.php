<?php
// Database (Model)
namespace Database;

class Model {
    private $host_name = '***************';
    private $database = '***************';
    private $user_name = '***************';
    private $password = '***************';

    protected function connect() {
        try {
            $pdo = new \PDO("mysql:host=$this->host_name; dbname=$this->database;", $this->user_name, $this->password);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            echo "Error!:" . $e->getMessage();
            die();
        }
    } 
}
