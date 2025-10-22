<?php
// Remove Option Value Assoc (Model)
namespace API\Option\Assoc\Value\Remove;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->valID) && isset($this->itemID)) {
            $sql = "DELETE FROM itemOptValAssoc WHERE valID = ? AND itemID = ?;";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->valID, $this->itemID])) {
                return true;
            }

            $statement = null;
        }
    }
}