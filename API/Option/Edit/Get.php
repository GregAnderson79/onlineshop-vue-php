<?php
// Get option data to edit (Controller)
namespace API\Option\Edit;

class Get extends Model {
    public $optID;

    public function __construct($optID) {
        $this->optID = $optID;
    }

    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}