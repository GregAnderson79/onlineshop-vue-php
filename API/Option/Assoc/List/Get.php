<?php
// Get list of options, values with associations (Controller)
namespace API\Option\Assoc\List;
use API\Option\Assoc\Value\List\Get as GetValList;
use API\Option\Assoc\Get as GetOptAssoc;

class Get extends Model {
    public $itemID;

    public function __construct($itemID) {
        $this->itemID = $itemID;
    } 

    public function returnData() {
        $opts = $this->privateQuery();
        if ($opts) {
            $rtnOpts = [];
            foreach($opts as $i) {
                // is this option associated with this item?
                $getOptAssoc = new GetOptAssoc($i['optID'], $this->itemID);
                $isOptAssoc = $getOptAssoc->returnData();

                // add values if assoc exists
                if ($isOptAssoc) {
                    // check for values
                    $getValList = new GetValList($i['optID'], $this->itemID);
                    $rtnVals = $getValList->returnData();

                    // merge values
                    if ($rtnVals) {
                        // check vals for assoc - set opt canRmv to false is any exist
                        $canRmv = true;
                        foreach($rtnVals as $j) {
                            if ($j['isAssoc'] === true) {
                                $canRmv = false;
                            }
                        }

                        $rtnOpts[] = ['type' => 'opt', 'ovID' => $i['optID'], 'ovName' => $i['optName'], 'canRmv' => $canRmv, 'isAssoc' => $isOptAssoc];
                        $rtnOpts = array_merge($rtnOpts, $rtnVals);
                    } else {
                        $rtnOpts[] = ['type' => 'opt','ovID' => $i['optID'], 'ovName' => $i['optName'], 'canRmv' => true, 'isAssoc' => $isOptAssoc];
                    }
                } else {
                    $rtnOpts[] = ['type' => 'opt','ovID' => $i['optID'], 'ovName' => $i['optName'], 'canRmv' => true, 'isAssoc' => $isOptAssoc];
                }
            }

            return $rtnOpts;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}