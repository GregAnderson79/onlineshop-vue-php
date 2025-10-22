<?php
// Get main categories (Contr)
namespace API\Category\List\MobNav;
use API\Category\RowCount\Subs\Get as GetSubRows;
use API\Category\RowCount\Items\Get as GetItemRows;
use API\Category\List\Sub\Get as GetSubCats;

class Get extends Model {
    public function returnData() {
        $mainCats = $this->privateQuery();
        if ($mainCats) {
            $rtnMainCats = [];
            foreach ($mainCats as $i) {
                $canDelete = false;
                
                // any sub cats?
                $subCatRows = new GetSubRows($i['catID']);
                $canDel1 = $subCatRows->returnData();

                // any items?
                $itemRows = new GetItemRows($i['catID']);
                $canDel2 = $itemRows->returnData();

                // cannot delete if populated with sub cats or items
                if ($canDel1 !== true && $canDel2 !== true) {
                    $canDelete = true;
                }

                $rtnMainCats[] = ['type' => 'main', 'catID' => $i['catID'], 'catName' => $i['catName'], 'catStatus' => $i['catStatus'], 'pos' => $i['pos'], 'canDelete' => $canDelete];

                // get any sub cats
                $subCats = new GetSubCats($i['catID']);
                $subCatArr = $subCats->returnData();
                if ($subCatArr) {
                    $rtnMainCats = array_merge($rtnMainCats, $subCatArr);
                }
            }
            return $rtnMainCats;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}