<?php
// Get PayPal email (Controller)
namespace API\PayPal\Edit;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        $sql = "SELECT payPalEmail FROM payPalEmail ORDER BY ppID DESC LIMIT 1";
        $statement = $this->connect()->prepare($sql);

        if (!$statement->execute()) {
            $statement = null;
            exit;
        }

        if ($statement->rowCount() > 0) {
            return $statement->fetch();
        }
        
        $statement = null;
    }
}