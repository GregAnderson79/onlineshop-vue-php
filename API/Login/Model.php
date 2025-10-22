<?php
// Get login details (Model)
namespace API\Login;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->loginEmail)) {
            $sql = "SELECT adminID, adminName, adminPwd FROM admins WHERE adminEmail = ? ";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->loginEmail])) {
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