<?php
// Get option values for column (Controller)
namespace API\Option\Value\List;

class Get extends Model {
    public $optID;

    public function __construct($optID) {
        $this->optID = $optID;
    }

    public function returnData() {
        $values = $this->privateQuery();
        if ($values) {
            $rtnVals = [];
            foreach ($values as $i) {
                $rtnVals[] = ['type' => 'val','ovID' => $i['valID'], 'ovName' => $i['valName']];
            }
            return $rtnVals;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}
