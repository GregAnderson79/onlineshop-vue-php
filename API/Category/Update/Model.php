<?php
// Set Category (Model)
namespace API\Category\Update;
use Database\Model as DB;

class Model extends DB {
    protected function query() {   
        if (isset($this->catID)) {
            $sql = "UPDATE categories SET catName=?, catDesc=?, parID=?, catStatus=? WHERE catID=?";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->catName, $this->catDesc, $this->parID, $this->catStatus, $this->catID])) {
                return true;
            }
            $statement = null;
        }
    }
}