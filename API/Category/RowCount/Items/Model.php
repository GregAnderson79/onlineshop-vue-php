<?php
// Get rowcount of items inside a main cat (Model)
namespace API\Category\RowCount\Items;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->catID)) {
            $sql = "SELECT null FROM items WHERE catID = ?";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->catID])) {
                $statement = null;
                exit();
            }

            if ($statement->rowCount() > 0) {
                return true;
            }

            $statement = null;
        }
    }
}