<?php
// Get main categories (Contr)
namespace API\Category\List\Sub;
use API\Category\RowCount\Items\Get as GetItemRows;

class Get extends Model {
    public $catID;

    public function __construct($catID) {
        $this->catID = $catID;
    }

    public function returnData() {
        $subCats = $this->privateQuery();
        if ($subCats) {
            $rtnSubCats = [];
            foreach ($subCats as $i) {
                $canDelete = false;

                // any items?
                $itemRows = new GetItemRows($i['catID']);
                $canDelete = !$itemRows->returnData();

                $rtnSubCats[] = ['catID' => $i['catID'], 'catName' => $i['catName'], 'catStatus' => $i['catStatus'], 'pos' => $i['pos'], 'canDelete' => $canDelete];
            }
            return $rtnSubCats;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}