<?php
// Set image to main (Model)
namespace API\Item\Images\Main;
use Database\Model as DB;

class Model extends DB {

    // unset current main image
    protected function queryOld() {
        if (isset($this->itemID)) {
            $sql = "UPDATE itemImages SET isMain=false WHERE itemID=? AND isMain=true";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->itemID])) {
                return true;
            }
            
            $statement = null;
        }
    }

    // set new main
    protected function queryNew() {
        if (isset($this->imgID)) {
            $sql = "UPDATE itemImages SET isMain=true WHERE imgID=?";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->imgID])) {
                return true;
            }
            $statement = null;
        }
    }
}