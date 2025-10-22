<?php
// Get assoc between item and option value (Model)
namespace API\Option\Assoc\Value;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->valID) && isset($this->itemID)) {
            $sql = "SELECT null FROM itemOptValAssoc WHERE valID = ? AND itemID = ?";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->valID, $this->itemID])) {
                $statement = null;
                exit;
            }

            if ($statement->rowCount() > 0) {
                return true;
            }

            $statement = null;
        }
    }
}