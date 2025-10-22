<?php
// Delete item image (Controller)
namespace API\Item\Images\Delete;

class Set extends Model {
    public $imgID;
    public $imgName;

    public function __construct($imgID, $imgName) {
        $this->imgID = $imgID;
        $this->imgName = $imgName;
    }

    public function process() {
        if (file_exists("../itemImages/resized_" . $this->imgName)) {
            unlink("../itemImages/resized_" . $this->imgName);
        }

        if (file_exists("../itemImages/thumb_" . $this->imgName)) {
            unlink("../itemImages/thumb_" . $this->imgName);
        }

        if (file_exists("../itemImages/cat_" . $this->imgName)) {
            unlink("../itemImages/cat_" . $this->imgName);
        }

        if ($this->privateQuery($this->imgID)) {
            return "deleted";
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}