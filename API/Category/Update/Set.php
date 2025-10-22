<?php
// Set Category (Controller)
namespace API\Category\Update;

class Set extends Model {
    public $catName;
    public $catDesc;
    public $parID;
    public $catStatus;
    public $catID;
        
    public function __construct($catName, $catDesc, $parID, $catStatus, $catID) {
        $this->catName = $catName;
        $this->catDesc = $catDesc;
        $this->parID = $parID;
        $this->catStatus = $catStatus;
        $this->catID = $catID;
    }

    public function process() {

        // validate, redirect
        if ($this->valNameLen() || $this->valCatSelf()) {
            if ($this->valNameLen()) {
                return "error name";
                exit;
            }

            if ($this->valCatSelf()) {
                return "error self";
                exit;
            }
        }

        // update cat
        if ($this->set() === true) {
            return "updated";
        } else {
            return "error not found";
            exit;
        }
    }

    // model
    private function set() {
        return $this->query();
    }

    // validate cat name length
    private function valNameLen() {
        if (strlen($this->catName) < 1) {
            return true;
        }
    }

    // validate - cat can't be put inside itself
    private function valCatSelf() {
        if ($this->catID) {
            if ($this->parID == $this->catID) {
                return true;
            }
        }
    }
}