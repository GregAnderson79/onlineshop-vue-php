<?php
// Get option value data to edit (Controller)
namespace API\Option\Value\Edit;

class Get extends Model {
    public $valID;

    public function __construct($valID) {
        $this->valID = $valID;
    }

    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}