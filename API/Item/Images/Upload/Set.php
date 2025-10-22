<?php
// Upload image to item (Controller)
namespace API\Item\Images\Upload;
use API\Item\Images\Upload\Name\Set as SetName;

class Set extends Model {
    public $tmpImgName;
    public $itemID;
    public $altTag;
    public $caption;

    public function __construct($tmpImgName, $itemID, $altTag, $caption) {
        $this->tmpImgName = $tmpImgName;
        $this->itemID = $itemID;
        $this->altTag = $altTag;
        $this->caption = $caption;
    }

    public function process() {
        $file = "tempImages/" . $this->tmpImgName;

        // image name
        $setName = new SetName();
        $imgName = $setName->returnData();

        // max size
        define ("MAX_SIZE","4000");
        $size = filesize($file);
		if ($size > MAX_SIZE*1024) {
            $this->deleteOriginalFile();
            return "error size";
            exit;
		}

        $props = getimagesize($file);
        $imgType = $props[2];

        if (($imgType == IMAGETYPE_JPEG) || ($imgType == IMAGETYPE_GIF) || ($imgType == IMAGETYPE_PNG) || ($imgType == IMAGETYPE_WEBP)) {
            $width = $props[0];
            $height = $props[1];
            $imgName = date("YmdHis");

            $resizedWidth=1000;
            $resizedHeight=round(($height/$width)*$resizedWidth);

            $thumbWidth=150;
            $thumbHeight=round(($height/$width)*$thumbWidth);

            $catWidth=500;
            $catHeight=round(($height/$width)*$catWidth);

            if ($imgType == IMAGETYPE_JPEG) {
                $imgName = $imgName . ".jpg";
                $layer = imagecreatetruecolor($resizedWidth, $resizedHeight);
                imagecopyresampled($layer, imagecreatefromjpeg($file), 0, 0, 0, 0, $resizedWidth, $resizedHeight, $width, $height);
                imagejpeg($layer, "../itemImages/resized_" . $imgName);

                $layer = imagecreatetruecolor($thumbWidth, $thumbHeight);
                imagecopyresampled($layer, imagecreatefromjpeg($file), 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
                imagejpeg($layer, "../itemImages/thumb_" . $imgName);

                $layer = imagecreatetruecolor($catWidth, $catHeight);
                imagecopyresampled($layer, imagecreatefromjpeg($file), 0, 0, 0, 0, $catWidth, $catHeight, $width, $height);
                imagejpeg($layer, "../itemImages/cat_" . $imgName);

            } else if ($imgType == IMAGETYPE_GIF) {
                $imgName = $imgName . ".gif";
                $layer = imagecreatetruecolor($resizedWidth, $resizedHeight);
                imagecopyresampled($layer, imagecreatefromgif($file), 0, 0, 0, 0, $resizedWidth, $resizedHeight, $width, $height);
                imagegif($layer, "../itemImages/resized_" . $imgName);

                $layer = imagecreatetruecolor($thumbWidth, $thumbHeight);
                imagecopyresampled($layer, imagecreatefromgif($file), 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
                imagegif($layer, "../itemImages/thumb_" . $imgName);

                $layer = imagecreatetruecolor($catWidth, $catHeight);
                imagecopyresampled($layer, imagecreatefromgif($file), 0, 0, 0, 0, $catWidth, $catHeight, $width, $height);
                imagegif($layer, "../itemImages/cat_" . $imgName);

            } else if ($imgType == IMAGETYPE_PNG) {
                $imgName = $imgName . ".png";
                $layer = imagecreatetruecolor($resizedWidth, $resizedHeight);
                imagecopyresampled($layer, imagecreatefrompng($file), 0, 0, 0, 0, $resizedWidth, $resizedHeight, $width, $height);
                imagepng($layer, "../itemImages/resized_" . $imgName);

                $layer = imagecreatetruecolor($thumbWidth, $thumbHeight);
                imagecopyresampled($layer, imagecreatefrompng($file), 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
                imagepng($layer, "../itemImages/thumb_" . $imgName);

                $layer = imagecreatetruecolor($catWidth, $catHeight);
                imagecopyresampled($layer, imagecreatefrompng($file), 0, 0, 0, 0, $catWidth, $catHeight, $width, $height);
                imagepng($layer, "../itemImages/cat_" . $imgName);

            } else if ($imgType == IMAGETYPE_WEBP) {
                $imgName = $imgName . ".webp";
                $layer = imagecreatetruecolor($resizedWidth, $resizedHeight);
                imagecopyresampled($layer, imagecreatefromwebp($file), 0, 0, 0, 0, $resizedWidth, $resizedHeight, $width, $height);
                imagewebp($layer, "../itemImages/resized_" . $imgName);

                $layer = imagecreatetruecolor($thumbWidth, $thumbHeight);
                imagecopyresampled($layer, imagecreatefromwebp($file), 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
                imagewebp($layer, "../itemImages/thumb_" . $imgName);

                $layer = imagecreatetruecolor($catWidth, $catHeight);
                imagecopyresampled($layer, imagecreatefromwebp($file), 0, 0, 0, 0, $catWidth, $catHeight, $width, $height);
                imagewebp($layer, "../itemImages/cat_" . $imgName);
            }
        } else {
            $this->deleteOriginalFile();
            return "error type";
            exit;
        }

        $this->deleteOriginalFile();

        if ($this->privateQuery($imgName)) {
            return "uploaded";
        }
    }

    // add to database
    private function privateQuery($imgName) {
        return $this->query($imgName);
    }

    // delete original file
    private function deleteOriginalFile() {
        if (file_exists("tempImages/" . $this->tmpImgName)) {
            unlink("tempImages/" . $this->tmpImgName);
        }
    }
}