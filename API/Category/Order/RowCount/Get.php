<?php
// Get rowcount of sub / main cats (Contr)
namespace API\Category\Order\RowCount;

class Get extends Model {
    public $catID;

    public function __construct($catID) {
        $this->catID = $catID;
    }

    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}