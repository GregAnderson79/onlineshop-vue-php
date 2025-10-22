<?php
// Get item list (Model)
namespace API\Item\List;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->catID)) {
            $sql = "SELECT itemID, itemName, itemStatus FROM items WHERE catID = ?";
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