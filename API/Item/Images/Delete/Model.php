<?php
// Image list for item (Model)
namespace API\Item\Images\Delete;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->imgID)) {
            $sql = "DELETE FROM itemImages WHERE imgID = ?;";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->imgID])) {
                return true;
            }
            
            $statement = null;
        }
    }
}