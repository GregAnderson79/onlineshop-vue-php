<?php
// Set Option Value (Controller)
namespace API\Option\Value\Add;

class Set extends Model {
    public $valName;
    public $optID;
        
    public function __construct($valName, $optID) {
        $this->valName = $valName;
        $this->optID = $optID;
    }

    public function process() {

        // validate
        if ($this->valNameLen()) {
            return "error name";
            exit;
        }

        // add option value
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
        if (strlen($this->valName) < 1) {
            return true;
        }
    }
}