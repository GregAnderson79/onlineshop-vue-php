<?php
// Get admin with existing email (Controller)
namespace API\Admin\EmailExists;

class Get extends Model {
    public $adminEmail;

    public function __construct($adminEmail) {
        $this->adminEmail = $adminEmail;
    }

    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}