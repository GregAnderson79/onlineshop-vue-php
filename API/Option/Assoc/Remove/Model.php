<?php
// Remove Option Assoc (Model)
namespace API\Option\Assoc\Remove;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->optID) && isset($this->itemID)) {
            $sql = "DELETE FROM itemOptAssoc WHERE optID = ? AND itemID = ?;";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->optID, $this->itemID])) {
                return true; 
            }

            $statement = null;
        }
    }
}