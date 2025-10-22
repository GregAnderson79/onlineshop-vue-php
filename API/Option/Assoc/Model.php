<?php
// Get assoc between item and option (Model)
namespace API\Option\Assoc;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->optID) && isset($this->itemID)) {
            $sql = "SELECT null FROM itemOptAssoc WHERE optID = ? AND itemID = ?";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->optID, $this->itemID])) {
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