<?php
// Add option value assoc (Controller)
namespace API\Option\Assoc\Value\Add;

class Set extends Model {
    public $valID;
    public $itemID;
        
    public function __construct($valID, $itemID) {
        $this->valID = $valID;
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