<?php
// Get list of option, values with associations (Model)
namespace API\Option\Assoc\List;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        $sql = "SELECT optID, optName FROM options ORDER BY optName";
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