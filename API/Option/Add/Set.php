<?php
// Set Option (Controller)
namespace API\Option\Add;

class Set extends Model {
    public $optName;
        
    public function __construct($optName) {
        $this->optName = $optName;
    }

    public function process() {

        // validate
        if ($this->valNameLen()) {
            return "error name";
            exit;
        }

        // add option
        if ($this->privateQuery()) {
            return "added";
        }
    }

    // model
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