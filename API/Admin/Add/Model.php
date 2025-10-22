<?php
// Add admin (Model)
namespace API\Admin\Add;
use Database\Model as DB;

class Model extends DB {
    protected function query() {            
        $sql = "INSERT INTO admins (adminName, adminEmail, adminPwd) VALUES (?, ?, ?);";
        $statement = $this->connect()->prepare($sql);
        
        if (!$statement->execute([$this->adminName, $this->adminEmail, $this->adminPwd])) {
            return false;
        } else {
            return true;
        }
        $statement = null;
    }
}