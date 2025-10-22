<?php
// Get rowcount of items inside a main cat (Model)
namespace API\Category\RowCount\Items;

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