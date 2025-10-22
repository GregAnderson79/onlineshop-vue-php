<?php
// Database (Model)
namespace Database;

class Model {
    private $host_name = 'db5017638471.hosting-data.io';
    private $database = 'dbs14112778';
    private $user_name = 'dbu4611353';
    private $password = 'jakiiri28839';

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