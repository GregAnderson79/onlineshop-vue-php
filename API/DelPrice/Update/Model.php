<?php
// Update delivery price (Model)
namespace API\DelPrice\Update;
use Database\Model as DB;

class Model extends DB {
    protected function query() {         
        if (isset($this->delPrice)) {
            $sql = "UPDATE delivery SET delPrice=? ORDER BY dpID DESC LIMIT 1";
            $statement = $this->connect()->prepare($sql);
        
            if ($statement->execute([$this->delPrice])) {
                return true;
            } else {
                return false;
            }
            $statement = null;
        }
    }
}