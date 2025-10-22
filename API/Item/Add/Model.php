<?php
// Set Category (Model)
namespace API\Item\Add;
use Database\Model as DB;

class Model extends DB {
    protected function query() {            
        $sql = "INSERT INTO items (itemName, catID, itemPrice, itemStatus, itemStock, itemDesc1, itemDesc2, itemDesc3) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
        $statement = $this->connect()->prepare($sql);
        
        if (!$statement->execute([$this->itemName, $this->catID, $this->itemPrice, $this->itemStatus, $this->itemStock, $this->itemDesc1, $this->itemDesc2, $this->itemDesc3])) {
            return false;
        } else {
            return true;
        }
        $statement = null;
    }
}