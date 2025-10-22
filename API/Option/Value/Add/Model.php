<?php
// Set Option Value (Model)
namespace API\Option\Value\Add;
use Database\Model as DB;

class Model extends DB {
    protected function query() { 
        if (isset($this->valName) && isset($this->optID)) {    
            $sql = "INSERT INTO optionValues (valName, optID) VALUES (?, ?);";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->valName, $this->optID])) {
                return true;
            }
            
            $statement = null;
        }
    }
}