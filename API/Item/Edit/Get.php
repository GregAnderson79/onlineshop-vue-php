<?php
// Get item data to edit (Controller)
namespace API\Item\Edit;

class Get extends Model {
    public $itemID;

    public function __construct($itemID) {
        $this->itemID = $itemID;
    }

    public function returnData() {
        if ($this->privateQuery()) {
            return $this->privateQuery();
        } else {
            return null;
        }
        
    }

    private function privateQuery() {
        return $this->query();
    }
}