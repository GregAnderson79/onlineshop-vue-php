<?php
// Set Category (Controller)
namespace API\Category\Order\Others;

class Set extends Model {
    public $parID;
    public $newPos;
    public $catPos;
        
    public function __construct($parID, $newPos, $catPos) {
        $this->parID = $parID;
        $this->newPos = $newPos;
        $this->catPos = $catPos;
    }

    public function process() {
        if ($this->privateQuery() === true) {
            return "updated";
        } else {
            return "error not found";
            exit;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}