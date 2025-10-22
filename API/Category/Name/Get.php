<?php
// Get category name (Contr)
namespace API\Category\Name;

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