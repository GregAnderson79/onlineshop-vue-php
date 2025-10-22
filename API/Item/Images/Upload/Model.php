<?php
// Upload image to item (Model)
namespace API\Item\Images\Upload;
use Database\Model as DB;

class Model extends DB {

    // set item image
    protected function query($imgName) {
        if ($this->itemID) {
            $sql = "INSERT INTO itemImages (imgName, itemID, isMain, altTag, caption) VALUES (?, ?, ?, ?, ?);";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$imgName, $this->itemID, false, $this->altTag, $this->caption])) {
                return true;
            }

            $statement = null;
        }
    }
}