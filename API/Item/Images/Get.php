<?php
// Image list for item (Controller)
namespace API\Item\Images;

class Get extends Model {
    public $itemID;

    public function __construct($itemID) {
        $this->itemID = $itemID;
    }

    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}