<?php
// Get option values for column (Model)
namespace API\Option\Value\List;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->optID)) {
            $sql = "SELECT valID, valName, pos FROM optionValues WHERE optID = ? ORDER BY pos";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->optID])) {
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