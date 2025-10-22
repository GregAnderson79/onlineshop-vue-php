<?php
// Get rowcount of sub cats inside a main cat (Model)
namespace API\Category\Order\RowCount;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->catID)) {
            $sql = "SELECT null FROM categories WHERE parID = ?";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->catID])) {
                $statement = null;
                exit();
            }

            return $statement->rowCount();
            $statement = null;
        }
    }
}