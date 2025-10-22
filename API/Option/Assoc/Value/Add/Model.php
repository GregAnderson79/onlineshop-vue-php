<?php
// Add option value assoc (Model)
namespace API\Option\Assoc\Value\Add;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->valID) && isset($this->itemID)) {          
            $sql = "INSERT INTO itemOptValAssoc (valID, itemID) VALUES (?, ?);";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->valID, $this->itemID])) {
                return true;
            }

            $statement = null;
        }
    }
}