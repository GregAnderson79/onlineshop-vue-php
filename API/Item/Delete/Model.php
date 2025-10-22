<?php
// delete Item (Model)
namespace API\Item\Delete;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->itemID)) {
            $sql = "DELETE FROM items WHERE itemID = ?;";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->itemID])) {
                return true; 
            }
            
            $statement = null;
        }
    }
}