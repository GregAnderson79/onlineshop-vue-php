<?php
// Update option (Controller)
namespace API\Option\Update;

class Set extends Model {
    public $optName;
    public $optID;
        
    public function __construct($optName, $optID) {
        $this->optName = $optName;
        $this->optID = $optID;
    }

    public function process() {

        // validate, redirect
        if ($this->valNameLen()) {
            return "error name";
            exit;
        }

        // update option
        if ($this->privateQuery()) {
            return "updated";
        }
    }

    private function privateQuery() {
        return $this->query();
    }

    // validate option name length
    private function valNameLen() {
        if (strlen($this->optName) < 1) {
            return true;
        }
    }
}