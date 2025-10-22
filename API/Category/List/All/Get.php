<?php

// Get all categories list (Contr)
namespace API\Category\List\All;
use API\Category\List\All\Sub\Get as GetSubCats;

class Get extends Model {
    public function returnData() {
        $mainCats = $this->privateQuery();
        if ($mainCats) {
            $rtnCats = [];
            foreach ($mainCats as $i) {
                $rtnCats[] = ['catID' => $i['catID'], 'catName' => $i['catName']];

                $getSubCats = new GetSubCats($i['catID']);
                $subCats = $getSubCats->returnData();
                if ($subCats) {
                    foreach ($subCats as $j) {
                        $rtnCats[] = ['catID' => $j['catID'], 'catName' => "- " . $j['catName']];
                    }
                }
            }
            return $rtnCats;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}