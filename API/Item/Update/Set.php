<?php
// Set Item (Controller)
namespace API\Item\Update;

class Set extends Model {
    public $itemName;
    public $catID;
    public $itemPrice;
    public $itemStatus;
    public $itemStock;
    public $itemDesc1;
    public $itemDesc2;
    public $itemDesc3;
    public $itemID;
        
    public function __construct($itemName, $catID, $itemPrice, $itemStatus, $itemStock, $itemDesc1, $itemDesc2, $itemDesc3, $itemID) {
        $this->itemName = $itemName;
        $this->catID = $catID;
        $this->itemPrice = $itemPrice;
        $this->itemStatus = $itemStatus;
        $this->itemStock = $itemStock;
        $this->itemDesc1 = $itemDesc1;
        $this->itemDesc2 = $itemDesc2;
        $this->itemDesc3 = $itemDesc3;
        $this->itemID = $itemID;
    }

    public function process() {

        // validate
        if ($this->valIDNumeric()) {
            return "error ID";
            exit;
        }

        if ($this->valNameLen()) {
            return "error 2";
            exit;
        }

        if ($this->valPriceNumeric()) {
            return "error 3";
            exit;
        }

        if ($this->valPriceFormat()) {
            return "error 3";
            exit;
        }

        if ($this->valStockNumeric()) {
            return "error 4";
            exit;
        }

        // update item
        if ($this->privateQuery()) {
            return "updated";
        }
    }

    // model
    private function privateQuery() {
        return $this->query();
    }

    // validate cat name length
    private function valNameLen() {
        if (strlen($this->itemName) < 3) {
            return true;
        }
    }

    // validate price is numeric
    private function valPriceNumeric() {
        if (!is_numeric($this->itemPrice)) {
           return true;
        }
    }

    // validate price is formatted correctly NN.NN
    private function valPriceFormat() {
        if (!preg_match("/^(0|[1-9]\d*)(\.\d{2})?$/", $this->itemPrice)) {
           return true;
        }
    }

    // validate stock is numeric
    private function valStockNumeric() {
        if (!is_numeric($this->itemStock)) {
           return true;
        }
    }

    // validate item ID
    private function valIDNumeric() {
        if (!is_numeric($this->itemID)) {
           return true;
        }
    }
}