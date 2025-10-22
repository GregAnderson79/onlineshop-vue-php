<?php
// Delete Option (Controller)
namespace API\Option\Delete;

class Set extends Model {
    public $optID;

    public function __construct($optID) {
        $this->optID = $optID;
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