<?php
// Set Option Value (Model)
namespace API\Option\Value\Update;
use Database\Model as DB;

class Model extends DB {
    protected function query() {    
        if (isset($this->valName) && isset($this->valID)) {
            $sql = "UPDATE optionValues SET valName=? WHERE valID=?";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->valName, $this->valID])) {
                return true;
            }

            $statement = null;
        }
    }
}