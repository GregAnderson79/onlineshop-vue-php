<?php
// Create unique image name (Controller)
namespace API\Item\Images\Upload\Name;

class Set {
    public function returnData() {
        return date("YmdHis");
    }
}