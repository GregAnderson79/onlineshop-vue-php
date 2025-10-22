<?php
// Item list for column (Controller)
namespace API\Item\List;

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