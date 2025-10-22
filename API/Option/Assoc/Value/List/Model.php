<?php
// Get list of values with associations (Model)
namespace API\Option\Assoc\Value\List;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->optID)) {
            $sql = "SELECT valID, valName FROM optionValues WHERE optID = ? ORDER BY valName";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->optID])) {
                $statement = null;
                exit;
            }

            if ($statement->rowCount() > 0) {
                return $statement->fetchAll();
            }

            $statement = null;
        }
    }
}