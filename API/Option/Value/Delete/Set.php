<?php
// Delete Option Value (Controller)
namespace API\Option\Value\Delete;

class Set extends Model {
    public $valID;

    public function __construct($valID) {
        $this->valID = $valID;
    }

    public function process() { 
        if ($this->privateQuery()) {
            return "deleted";
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}