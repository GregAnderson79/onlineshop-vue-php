<?php
// Get rowcount of sub cats inside a main cat (Model)
namespace API\Category\RowCount\Subs;

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