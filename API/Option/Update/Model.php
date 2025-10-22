<?php
// Set Option (Model)
namespace API\Option\Update;
use Database\Model as DB;

class Model extends DB {
    protected function query() {  
        if (isset($this->optName) && isset($this->optID)) {
            $sql = "UPDATE options SET optName=? WHERE optID=?";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->optName, $this->optID])) {
                return true;
            }

            $statement = null;
        }
    }
}