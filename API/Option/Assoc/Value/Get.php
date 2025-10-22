<?php
// Get assoc between item and option value (Controller)
namespace API\Option\Assoc\Value;

class Get extends Model {
    public $valID;
    public $itemID;

    public function __construct($valID, $itemID) {
        $this->valID = $valID;
        $this->itemID = $itemID;
    } 

    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}