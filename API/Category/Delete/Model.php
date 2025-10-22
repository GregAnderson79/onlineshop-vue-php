<?php
// delete Category (Model)
namespace API\Category\Delete;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->catID)) {
            $sql = "DELETE FROM categories WHERE catID = ?;";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->catID])) {
                return true;
            }
            
            $statement = null;
        }
    }
}