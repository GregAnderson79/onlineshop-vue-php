<?php
// Set Option (Model)
namespace API\Option\Add;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->optName)) {           
            $sql = "INSERT INTO options (optName) VALUES (?);";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->optName])) {
                return true;
            }

            $statement = null;
        }
    }
}