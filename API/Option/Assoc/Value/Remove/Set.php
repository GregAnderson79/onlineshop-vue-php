<?php
// Remove Option Value Assoc (Controller)
namespace API\Option\Assoc\Value\Remove;

class Set extends Model {
    public $valID;
    public $itemID;

    public function __construct($valID, $itemID) {
        $this->valID = $valID;
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