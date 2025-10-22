<?php
// Delete Item (Controller)
namespace API\Item\Delete;

class Set extends Model {
    public $itemID;

    public function __construct($itemID) {
        $this->itemID = $itemID;
    }

    public function process() { 
        if ($this->privateQuery()) {
            echo("deleted");
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}