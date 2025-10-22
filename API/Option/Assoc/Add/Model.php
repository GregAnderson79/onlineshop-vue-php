<?php
// Add option assoc (Model)
namespace API\Option\Assoc\Add;
use Database\Model as DB;

class Model extends DB {
    protected function query() {     
        if (isset($this->optID) && isset($this->itemID)) {  
            $sql = "INSERT INTO itemOptAssoc (optID, itemID) VALUES (?, ?);";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->optID, $this->itemID])) {
                return true;
            }

            $statement = null;
        }
    }
}