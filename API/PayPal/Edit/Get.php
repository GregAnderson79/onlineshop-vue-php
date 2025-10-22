<?php
// Get PayPal email (Controller)
namespace API\PayPal\Edit;

class Get extends Model {
    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}