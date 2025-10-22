<?php
// Delete admin (Controller)
namespace API\Admin\Delete;

class Set extends Model {
    public $adminID;

    public function __construct($adminID) {
        $this->adminID = $adminID;
    }

    public function process() { 
        if ($this->privateQuery()) {
            return "deleted";
        } else {
            return "error";
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}