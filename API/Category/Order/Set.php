<?php
// Set Category Order (Controller)
namespace API\Category\Order;
use API\Category\Order\Current\Get as GetPos;
use API\Category\Order\RowCount\Get as GetRowCount;
use API\Category\Order\Others\Set as SetPos;

class Set extends Model {
  public $catID;
  public $dir;

  public function __construct($catID, $dir) {
    $this->catID = $catID;
    $this->dir = $dir;
  }

  public function process() {

    // get current cat data
    $getCat = new GetPos($this->catID);
    $catData = $getCat->returnData();

    if (!$catData) {
      return "error not found";
      exit;
    }

    $catPos = $catData['pos'];
    if ($catPos === 0) {$catPos = 1;}
    $parID = $catData['parID'];

    // get total cats in parent or from top
    if (!$parID) {$parID = 0;}
    $getRowCount = new GetRowCount($parID);
    $totCats = $getRowCount->returnData();

    if (!$totCats) {
      return "error not moved";
      exit;
    }

    // calc new pos
    if ($this->dir === "up") {
      if ($catPos > 1) {
        $newPos = $catPos - 1;
      } else {
        $newPos = 1;
      }
    } else if ($this->dir === "down") {
      if ($catPos < $totCats) {
        $newPos = $catPos + 1;
      } else {
        $newPos = $totCats;
      }
    }

    // update any cats that have the same pos & par ID
    $setOthers = new SetPos($parID, $newPos, $catPos);
    $changeOtherCats = $setOthers->process();

    if ($changeOtherCats === "updated") {
      // update this cat with the new pos
      if ($this->privateQuery($newPos)) {
          return "updated";
      } else {
          return "error not moved";
          exit;
      }
    }
  }

  private function privateQuery($newPos) {
    return $this->query($newPos);
  }
}