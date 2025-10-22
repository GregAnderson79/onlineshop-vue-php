<?php
// Image list for item (Model)
namespace API\Item\Images;
use Database\Model as DB;

class Model extends DB {
    protected function query() {
        if (isset($this->itemID)) {
            $sql = "SELECT imgID, imgName, isMain, altTag, caption FROM itemImages WHERE itemID = ? ORDER BY imgID DESC";
            $statement = $this->connect()->prepare($sql);

            if (!$statement->execute([$this->itemID])) {
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