<?php
// Get encryption codes (Model)
namespace API\Admin\Encryption;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        $sql = "SELECT encMethod, encKey, encIV FROM encVars ORDER BY encID DESC LIMIT 1";
        $statement = $this->connect()->prepare($sql);

        if (!$statement->execute()) {
            $statement = null;
            exit;
        }

        $results;
        if ($statement->rowCount() > 0) {
            return $statement->fetch();
        }
        $statement = null;
    }
}