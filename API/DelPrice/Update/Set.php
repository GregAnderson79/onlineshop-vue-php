<?php
// Update delivery price (Controller)
namespace API\DelPrice\Update;

class Set extends Model {
    public $delPrice;
        
    public function __construct($delPrice) {
        $this->delPrice = $delPrice;
    }

    public function process() {

        if ($this->valPriceFormat()) {
            return "error money";
            exit;
        }

        if ($this->privateQuery()) { 
            return "updated";
        } else {
            return "error";
        }
    }

    // model
    private function privateQuery() {
        return $this->query();
    }

    // validate price
    private function valPriceFormat() {
        return !preg_match("/^(0|[1-9]\d*)(\.\d{2})?$/", $this->delPrice);
    }
}