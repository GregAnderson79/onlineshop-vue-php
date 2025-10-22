<?php
// Delete Category (Controller)
namespace API\Category\Delete;

class Set extends Model {
    public $catID;

    public function __construct($catID) {
        $this->catID = $catID;
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