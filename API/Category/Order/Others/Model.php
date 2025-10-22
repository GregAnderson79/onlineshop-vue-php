<?php
// Set Category (Model)
namespace API\Category\Order\Others;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->catPos) && isset($this->parID) && isset($this->newPos)) { 
            $sql = "UPDATE categories SET pos=? WHERE parID=? AND pos=?";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->catPos, $this->parID, $this->newPos])) {
                return true;
            }

            $statement = null;
        }
    }
}