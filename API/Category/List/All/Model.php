<?php
// Get main categories (Model)
namespace API\Category\List\All;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        $sql = "SELECT catID, catName FROM categories WHERE parID = 0 ORDER BY pos";
        $statement = $this->connect()->prepare($sql);

        if (!$statement->execute()) {
            $statement = null;
            exit();
        }

        if ($statement->rowCount() > 0) {
            return $statement->fetchAll();
        }

        $statement = null;
    }
}