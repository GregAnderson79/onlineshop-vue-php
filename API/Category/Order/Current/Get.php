<?php
// Get Current Category Data (Controller)
namespace API\Category\Order\Current;

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