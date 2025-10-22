<?php
// Set Item (Model)
namespace API\Item\Update;
use Database\Model as DB;

class Model extends DB {
    protected function query() { 
        if (isset($this->itemID)) { 
            $sql = "UPDATE items SET itemName=?, catID=?, itemPrice=?, itemStatus=?, itemStock=?, itemDesc1=?, itemDesc2=?, itemDesc3=? WHERE itemID=?";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->itemName, $this->catID, $this->itemPrice, $this->itemStatus, $this->itemStock, $this->itemDesc1, $this->itemDesc2, $this->itemDesc3, $this->itemID])) {
                return true;
            }

            $statement = null;
        }
    }
}