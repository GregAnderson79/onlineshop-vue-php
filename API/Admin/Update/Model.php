<?php
// Update admin (Model)
namespace API\Admin\Update;
use Database\Model as DB;

class Model extends DB {
    protected function query() {         
        if (isset($this->adminID)) {
            $sql = "UPDATE admins SET adminName=?, adminEmail=?, adminPwd=? WHERE adminID=?";
            $statement = $this->connect()->prepare($sql);
        
            if ($statement->execute([$this->adminName, $this->adminEmail, $this->adminPwd, $this->adminID])) {
                return true;
            } else {
                return false;
            }
            $statement = null;
        }
    }
}