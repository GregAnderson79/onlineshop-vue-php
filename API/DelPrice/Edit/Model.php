<?php
// Get delivery price (Model)
namespace API\DelPrice\Edit;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        $sql = "SELECT delPrice FROM delivery ORDER BY dpID DESC LIMIT 1";
        $statement = $this->connect()->prepare($sql);

        if (!$statement->execute()) {
            $statement = null;
            exit;
        }

        if ($statement->rowCount() > 0) {
            return $statement->fetch();
        }
        
        $statement = null;
    }
}