<?php
// delete admin (Model)
namespace API\Admin\Delete;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->adminID)) {
            $sql = "DELETE FROM admins WHERE adminID = ?;";
            $statement = $this->connect()->prepare($sql);
            
            if ($statement->execute([$this->adminID])) {
                return true;
            } else {
                return false; 
            }
            $statement = null;
        } else {
            return false;
        }
    }
}