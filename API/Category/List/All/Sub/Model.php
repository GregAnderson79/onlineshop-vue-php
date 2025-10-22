<?php
// Get sub categories (Model)
namespace API\Category\List\All\Sub;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->catID)) {
            $sql = "SELECT catID, catName FROM categories WHERE parID = ? ORDER BY pos";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->catID])) {
                $statement = null;
                exit();
            }

            if ($statement->rowCount() > 0) {
                return $statement->fetchAll();
            }

            $statement = null;
        }
    }
}