<?php
// Get list of values with associations (Controller)
namespace API\Option\Assoc\Value\List;
use API\Option\Assoc\Value\Get as GetValAssoc;

class Get extends Model {
    public $optID;
    public $itemID;

    public function __construct($optID, $itemID) {
        $this->optID = $optID;
        $this->itemID = $itemID;
    }

    public function returnData() {
        $values = $this->privateQuery();
        if ($values) {
            $rtnVals = [];
            foreach ($values as $i) {

                // is this value associated with this item?
                $getValAssoc = new GetValAssoc($i['valID'], $this->itemID);
                $isValAssoc = $getValAssoc->returnData();

                $rtnVals[] = ['type' => 'val','ovID' => $i['valID'], 'ovName' => $i['valName'], 'isAssoc' => $isValAssoc];
            }
            return $rtnVals;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}
