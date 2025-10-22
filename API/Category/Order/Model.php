<?php
// Set Category (Model)
namespace API\Category\Order;
use Database\Model as DB;

class Model extends DB {
    protected function query($newPos) {    
        if (isset($newPos) && isset($this->catID)) {
            $sql = "UPDATE categories SET pos=? WHERE catID=?";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$newPos, $this->catID])) {
                return true;
            }
            
            $statement = null;
        }
    }
}