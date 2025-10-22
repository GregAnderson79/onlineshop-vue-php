<?php
// Get admin (Model)
namespace API\Admin\Edit;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->adminID)) {
            $sql = "SELECT adminName, adminEmail, adminPwd FROM admins WHERE adminID = ? ";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->adminID])) {
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