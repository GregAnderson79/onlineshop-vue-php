<?php
// Get item data to edit (Model)
namespace API\Item\Edit;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->itemID)) {
            $sql = "SELECT itemName, catID, itemPrice, itemStatus, itemStock, itemDesc1, itemDesc2, itemDesc3 FROM items WHERE itemID = ? ";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->itemID])) {
                $statement = null;
                exit;
            }

            if ($statement->rowCount() > 0) {
                return $statement->fetch();
            }

            $statement = null;
        }
    }
}