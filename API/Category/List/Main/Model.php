<?php
// Get main categories (Model)
namespace API\Category\List\Main;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        $sql = "SELECT catID, catName, catStatus, pos FROM categories WHERE parID = 0 ORDER BY pos";
        $statement = $this->connect()->prepare($sql);

        if (!$statement->execute()) {
            $statement = null;
            exit();
        }

        $results;
        if ($statement->rowCount() > 0) {
            $results = $statement->fetchAll();
        }

        $statement = null;
        return $results;
    }
}