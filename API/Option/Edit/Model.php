<?php
// Get option data to edit (Model)
namespace API\Option\Edit;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->optID)) {
            $sql = "SELECT optName FROM options WHERE optID = ? ";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->optID])) {
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