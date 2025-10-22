<?php
// Get encryption codes (Controller)
namespace API\Admin\Encryption;

class Get extends Model {
    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}