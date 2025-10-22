<?php
// Set Category (Controller)
namespace API\Category\Add;

class Set extends Model {
    public $catName;
    public $catDesc;
    public $parID;
    public $catStatus;
        
    public function __construct($catName, $catDesc, $parID, $catStatus) {
        $this->catName = $catName;
        $this->catDesc = $catDesc;
        $this->parID = $parID;
        $this->catStatus = $catStatus;
    }

    public function process() {

        // validate
        if ($this->valNameLen()) {
            return "error name";
            exit;
        }

        // add cat
        if ($this->set() === true) {
            return "added";
        }
    }

    // model
    private function set() {
        return $this->query();
    }

    // validate cat name length
    private function valNameLen() {
        if (strlen($this->catName) < 3) {
            return true;
        }
    }
}