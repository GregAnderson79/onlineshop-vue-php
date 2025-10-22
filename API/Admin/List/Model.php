<?php
// Get admins (Model)
namespace API\Admin\List;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        $sql = "SELECT adminName, adminID FROM admins ORDER BY adminName";
        $statement = $this->connect()->prepare($sql);

        if (!$statement->execute()) {
            $statement = null;
            exit;
        }

        $results;
        if ($statement->rowCount() > 0) {
            $results = $statement->fetchAll();
        } else {
            $results = null;
        }

        $statement = null;
        return $results;
    }
}