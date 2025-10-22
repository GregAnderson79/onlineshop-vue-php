<?php
// delete Option (Model)
namespace API\Option\Value\Delete;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->valID)) {
            $sql = "DELETE FROM optionValues WHERE valID = ?;";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->valID])) {
                return true; 
            }
            
            $statement = null;
        }
    }
}