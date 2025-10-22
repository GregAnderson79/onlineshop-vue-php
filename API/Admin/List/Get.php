<?php
// Get admins (Controller)
namespace API\Admin\List;

class Get extends Model {
    public function returnData() {
        return $this->privateQuery();
    }

    private function privateQuery() {
        return $this->query();
    }
}