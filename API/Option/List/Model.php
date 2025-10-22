<?php
// Get list of options (Model)
namespace API\Option\List;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        $sql = "SELECT optID, optName, optName, pos FROM options ORDER BY pos";
        $statement = $this->connect()->prepare($sql);

        if (!$statement->execute()) {
            $statement = null;
            exit;
        }

        if ($statement->rowCount() > 0) {
            return $statement->fetchAll();
        }

        $statement = null;
    }
}