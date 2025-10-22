<?php
// Get orders (Controller)
namespace API\Order\List;

class Get extends Model {
    public function returnData() {
        $orders = $this->privateQuery();
        if ($orders) {
            foreach($orders as &$i) {
               $i['items'] = json_decode($i['items'], true);
            }
            return $orders;
        } else {
            return null;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}