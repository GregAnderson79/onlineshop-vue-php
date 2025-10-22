<?php
// Get Current Category Data (Model)
namespace API\Category\Order\Current;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->catID)) {
            $sql = "SELECT parID, pos FROM categories WHERE catID = ? ";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->catID])) {
                $statement = null;
                exit();
            }

            if ($statement->rowCount() > 0) {
                return $statement->fetch();
            }

            $statement = null;
        }
    }
}