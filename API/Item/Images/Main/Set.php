<?php
// Set image to main (Controller)
namespace API\Item\Images\Main;

class Set extends Model {
    public $imgID;
    public $itemID;

    public function __construct($imgID, $itemID) {
        $this->imgID = $imgID;
        $this->itemID = $itemID;
    }

    public function process() {
        if ($this->privateQueryOld()) {
            if ($this->privateQueryNew()) {
                return "updated";
            }
        }        
    }

    private function privateQueryOld() {
        return $this->queryOld();
    }

    private function privateQueryNew() {
        return $this->queryNew();
    }
}