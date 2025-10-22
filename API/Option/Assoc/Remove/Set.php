<?php
// Remove Option Assoc (Controller)
namespace API\Option\Assoc\Remove;

class Set extends Model {
    public $optID;
    public $itemID;

    public function __construct($optID, $itemID) {
        $this->optID = $optID;
        $this->itemID = $itemID;
    }

    public function process() { 
        if ($this->privateQuery()) {
            return "removed";
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}