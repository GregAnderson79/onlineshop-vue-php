<?php
// Add option assoc (Controller)
namespace API\Option\Assoc\Add;

class Set extends Model {
    public $optID;
    public $itemID;
        
    public function __construct($optID, $itemID) {
        $this->optID = $optID;
        $this->itemID = $itemID;
    }

    public function process() {
        if ($this->privateQuery()) {
            return "added";
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}