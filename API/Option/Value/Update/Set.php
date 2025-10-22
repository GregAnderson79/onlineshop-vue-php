<?php
// Update option value (Controller)
namespace API\Option\Value\Update;

class Set extends Model {
    public $valName;
    public $valID;
        
    public function __construct($valName, $valID) {
        $this->valName = $valName;
        $this->valID = $valID;
    }

    public function process() {

        // validate, redirect
        if ($this->valNameLen()) {
            return "error name";
            exit;
        }

        // update option value
        if ($this->privateQuery()) {
            return "updated";
        }
    }

    private function privateQuery() {
        return $this->query();
    }

    // validate option value name length
    private function valNameLen() {
        if (strlen($this->valName) < 1) {
            return true;
        }
    }
}