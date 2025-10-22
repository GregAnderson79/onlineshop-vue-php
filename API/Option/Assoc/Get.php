<?php
// Get assoc between item and option (Controller)
namespace API\Option\Assoc;

class Get extends Model {
    public $optID;
    public $itemID;

    public function __construct($optID, $itemID) {
        $this->optID = $optID;
        $this->itemID = $itemID;
    } 

    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}