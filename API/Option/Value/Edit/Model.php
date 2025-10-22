<?php
// Get option value data to edit (Model)
namespace API\Option\Value\Edit;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->valID)) {
            $sql = "SELECT valName FROM optionValues WHERE valID = ? ";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->valID])) {
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