<?php
// Get delivery price (Controller)
namespace API\DelPrice\Edit;

class Get extends Model {
    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}