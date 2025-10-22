<?php
// delete Option (Model)
namespace API\Option\Delete;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->optID)) {
            $sql = "DELETE FROM options WHERE optID = ?;";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->optID])) {
                return true; 
            }

            $statement = null;
        }
    }
}