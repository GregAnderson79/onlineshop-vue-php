<?php
// Update PayPal (Model)
namespace API\PayPal\Update;
use Database\Model as DB;

class Model extends DB {
    protected function query() {         
        if (isset($this->payPalEmail)) {
            $sql = "UPDATE payPalEmail SET payPalEmail=? ORDER BY ppID DESC LIMIT 1";
            $statement = $this->connect()->prepare($sql);
        
            if ($statement->execute([$this->payPalEmail])) {
                return true;
            } else {
                return false;
            }
            $statement = null;
        }
    }
}