<?php
// Get list of options (Controller)
namespace API\Option\List;
use API\Option\Value\List\Get as GetValues;

class Get extends Model {
    public function returnData() {
        $opts = $this->privateQuery();
        if ($opts) {
            $rtnOpts = [];
            foreach($opts as $i) {
                // check for values
                $values = new GetValues($i['optID']);
                $rtnVals = $values->returnData();

                if ($rtnVals) {
                    $rtnOpts[] = ['type' => 'opt','ovID' => $i['optID'], 'ovName' => $i['optName'], 'canDelete' => false];
                    $rtnOpts = array_merge($rtnOpts, $rtnVals);
                } else {
                    $rtnOpts[] = ['type' => 'opt','ovID' => $i['optID'], 'ovName' => $i['optName'], 'canDelete' => true];
                }
            }

            return $rtnOpts;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}