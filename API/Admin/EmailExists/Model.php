<?php
// Get admin with existing email (Model)
namespace API\Admin\EmailExists;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->adminEmail)) {
            $sql = "SELECT null FROM admins WHERE adminEmail = ? ";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->adminEmail])) {
                $statement = null;
                return "error";
                exit;
            }

            if ($statement->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
            $statement = null;
            $sql = null;

        } else {
            return "error";
            exit;
        }
    }
}