<?php
// Set Category (Model)
namespace API\Category\Add;
use Database\Model as DB;

class Model extends DB {
    protected function query() {            
        $sql = "INSERT INTO categories (catName, catDesc, parID, catStatus) VALUES (?, ?, ?, ?);";
        $statement = $this->connect()->prepare($sql);
        
        if ($statement->execute([$this->catName, $this->catDesc, $this->parID, $this->catStatus])) {
            return true;
        }
        $statement = null;
    }
}