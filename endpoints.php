<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: https://**********");
header("Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

error_reporting(E_ALL);
ini_set('display_errors', 1);

use API\Category\List\MobNav\Get as GetMobNav;
use API\Category\List\Main\Get as GetMainCats;
use API\Category\List\Sub\Get as GetSubCats;
use API\Category\Name\Get as GetCatName;
use API\Item\List\Get as GetItemList;
use API\Option\List\Get as GetOptList;
use API\Category\Add\Set as AddCat;
use API\Category\Delete\Set as DelCat;
use API\Category\Edit\Get as GetCat;
use API\Category\Update\Set as UpdCat;
use API\Category\Order\Set as CatOrder;
use API\Category\List\All\Get as GetAllCatsList;
use API\Item\Add\Set as AddItem;
use API\Item\Delete\Set as DelItem;
use API\Item\Edit\Get as GetItem;
use API\Item\Update\Set as UpdItem;
use API\Item\Images\Get as GetImgs;
use API\Item\Images\Upload\Set as UplImg;
use API\Item\Images\Delete\Set as DelImg;
use API\Item\Images\Main\Set as MainImg;
use API\Option\Add\Set as AddOpt;
use API\Option\Delete\Set as DelOpt;
use API\Option\Edit\Get as GetOpt;
use API\Option\Update\Set as UpdOpt;
use API\Option\Value\Add\Set as AddOptVal;
use API\Option\Value\Delete\Set as DelOptVal;
use API\Option\Value\Edit\Get as GetOptVal;
use API\Option\Value\Update\Set as UpdOptVal;
use API\Option\Assoc\List\Get as GetAssocList;
use API\Option\Assoc\Add\Set as AddOptAssoc;
use API\Option\Assoc\Remove\Set as RmvOptAssoc;
use API\Option\Assoc\Value\Add\Set as AddOptValAssoc;
use API\Option\Assoc\Value\Remove\Set as RmvOptValAssoc;
use API\Order\List\Get as GetOrders;
use API\Admin\List\Get as GetAdmins;
use API\Admin\Add\Set as AddAdmin;
use API\Admin\Delete\Set as DelAdmin;
use API\Admin\Edit\Get as GetAdmin;
use API\Admin\Update\Set as UpdAdmin;
use API\PayPal\Edit\Get as GetPayPal;
use API\PayPal\Update\Set as UpdPayPal;
use API\DelPrice\Edit\Get as GetDelPrice;
use API\DelPrice\Update\Set as UpdDelPrice;
use API\Login\Get as GetLogin;

include("includes/autoLoader.php");

// get object
$data = json_decode(file_get_contents("php://input"), true);
if ($data) {
    $func = htmlspecialchars($data['func']);
    if ($func) {

        // mobile nav
        if ($func === "Get Mobile Nav") {
            $mobNav = new GetMobNav();
            echo json_encode($mobNav->returnData());
            unset($mobNav);
        }

        // get main categories
        if ($func === "Get Main Categories") {
            $mainCats = new GetMainCats();
            echo json_encode($mainCats->returnData());
            unset($mainCats);
        }

        // get sub categories
        if ($func == "Get Sub Categories") {
            $catID = htmlspecialchars($data['catID']) ?? 0;
            $subCats = new GetSubCats($catID);
            echo json_encode($subCats->returnData());
            unset($subCats);
        }

        // get category name
        if ($func === "Get Category Name") {
            $catID = htmlspecialchars($data['catID']) ?? 0;
            $catName = new GetCatName($catID);
            echo json_encode($catName->returnData());
            unset($catName);
        }

        // get items
        if ($func === "Get Items") {
            $catID = htmlspecialchars($data['catID']) ?? 0;
            $items = new GetItemList($catID);
            echo json_encode($items->returnData());
            unset($items);
        }

        // get options
        if ($func === "Get Options") {
            $opts = new GetOptList();
            echo json_encode($opts->returnData());
            unset($opts);
        }

        // add category
        if ($func === "Add Category") {
            $catName = htmlspecialchars($data['catName']) ?? "Unnamed category";
            $parID = htmlspecialchars($data['parID']) ?? 0;
            $catStatus = htmlspecialchars($data['catStatus']) ?? "active";
            if (isset($data['catDesc'])) {
                $catDesc = htmlspecialchars($data['catDesc']);
            } else {
                $catDesc = "";
            }

            $addCat = new AddCat($catName, $catDesc, $parID, $catStatus);
            echo json_encode($addCat->process());
            unset($addCat, $catName, $parID, $catStatus, $catDesc);
        }
        
        // delete category
        if ($func === "Delete Category") {
            $catID = htmlspecialchars($data['catID']);
            $delCat = new DelCat($catID);
            echo json_encode($delCat->process());
            unset($delCat, $catID);
        }

        // get category
        if ($func === "Get Category") {
            $catID = htmlspecialchars($data['catID']);
            $catData = new GetCat($catID);
            echo json_encode($catData->returnData());
            unset($catData, $catID);
        }

        // update category
        if ($func === "Update Category") {
            $catID = htmlspecialchars($data['catID']) ?? 0;
            $catName = htmlspecialchars($data['catName']) ?? "Unnamed category";
            $parID = htmlspecialchars($data['parID']) ?? 0;
            $catStatus = htmlspecialchars($data['catStatus']) ?? "active";
            if (isset($data['catDesc'])) {
                $catDesc = htmlspecialchars($data['catDesc']);
            } else {
                $catDesc = "";
            }
            $updCat = new UpdCat($catName, $catDesc, $parID, $catStatus, $catID);
            echo json_encode($updCat->process());
            unset($updCat, $catName, $parID, $catStatus, $catDesc, $catID);
        }

        // update cat order position
        if ($func === "Update Category Order") {
            $catID = htmlspecialchars($data['catID']) ?? 0;
            $dir = htmlspecialchars($data['dir']) ?? "up";
            $catOrder = new CatOrder($catID, $dir);
            echo json_encode($catOrder->process());
            unset($catOrder, $catID, $dir);
        }

        // get all categories
        if ($func === "Get All Categories List") {
            $getAllCatsList = new GetAllCatsList();
            echo json_encode($getAllCatsList->returnData());
            unset($getAllCatsList);
        }

        // add item
        if ($func === "Add Item") {
            $itemName = htmlspecialchars($data['data']['itemName']) ?? "Unnamed item";
            $catID = htmlspecialchars($data['data']['catID']) ?? 0;
            $itemPrice = htmlspecialchars($data['data']['itemPrice']) ?? 0;
            $itemStatus = htmlspecialchars($data['data']['itemStatus']) ?? "active";
            $itemStock = htmlspecialchars($data['data']['itemStock']) ?? 0;
            if (isset($data['data']['itemDesc1'])) {
                $itemDesc1 = htmlspecialchars($data['data']['itemDesc1']);
            } else {
                $itemDesc1 = "";
            }
            if (isset($data['data']['itemDesc2'])) {
                $itemDesc2 = htmlspecialchars($data['data']['itemDesc2']);
            } else {
                $itemDesc2 = "";
            }
            if (isset($data['data']['itemDesc3'])) {
                $itemDesc3 = htmlspecialchars($data['data']['itemDesc3']);
            } else {
                $itemDesc3 = "";
            }
            $addItem = new AddItem($itemName, $catID, $itemPrice, $itemStatus, $itemStock, $itemDesc1, $itemDesc2, $itemDesc3);
            echo $addItem->process();
            unset($addItem, $itemName, $catID, $itemPrice, $itemStatus, $itemStock, $itemDesc1, $itemDesc2, $itemDesc3);
        }

        // delete item
        if ($func === "Delete Item") {
            $itemID = htmlspecialchars($data['itemID']);
            $delItem = new DelItem($itemID);
            echo $delItem->process();
            unset($delItem, $itemID);
        }

        // get item
        if ($func === "Get Item") {
            $itemID = htmlspecialchars($data['itemID']);
            $getItem = new GetItem($itemID);
            echo json_encode($getItem->returnData());
            unset($getItem, $itemID);
        }

        // update item
        if ($func === "Update Item") {
            $itemID = htmlspecialchars($data['data']['itemID']) ?? 0;
            $itemName = htmlspecialchars($data['data']['itemName']) ?? "Unnamed item";
            $catID = htmlspecialchars($data['data']['catID']) ?? 0;
            $itemPrice = htmlspecialchars($data['data']['itemPrice']) ?? 0;
            $itemStatus = htmlspecialchars($data['data']['itemStatus']) ?? "active";
            $itemStock = htmlspecialchars($data['data']['itemStock']) ?? 0;
            if (isset($data['data']['itemDesc1'])) {
                $itemDesc1 = htmlspecialchars($data['data']['itemDesc1']);
            } else {
                $itemDesc1 = "";
            }
            if (isset($data['data']['itemDesc2'])) {
                $itemDesc2 = htmlspecialchars($data['data']['itemDesc2']);
            } else {
                $itemDesc2 = "";
            }
            if (isset($data['data']['itemDesc3'])) {
                $itemDesc3 = htmlspecialchars($data['data']['itemDesc3']);
            } else {
                $itemDesc3 = "";
            }
            $updItem = new UpdItem($itemName, $catID, $itemPrice, $itemStatus, $itemStock, $itemDesc1, $itemDesc2, $itemDesc3, $itemID);
            echo $updItem->process();
            unset($updItem, $itemName, $catID, $itemPrice, $itemStatus, $itemStock, $itemDesc1, $itemDesc2, $itemDesc3, $itemID);
        }

        // get item images
        if ($func === "Get Images") {
            $itemID = htmlspecialchars($data['itemID']) ?? 0;
            $imgs = new GetImgs($itemID);
            echo json_encode($imgs->returnData());
            unset($imgs);
        }

        // delete image
        if ($func === "Delete Image") {
            $imgID = htmlspecialchars($data['imgID']) ?? 0;
            $imgName = htmlspecialchars($data['imgName']) ?? "";
            $del = new DelImg($imgID, $imgName);
            echo $del->process();
            unset($del, $imgID, $imgName);
        }

        // set main image
        if ($func === "Set Main Image") {
            $imgID = htmlspecialchars($data['imgID']) ?? 0;
            $itemID = htmlspecialchars($data['itemID']) ?? 0;
            $mainImg = new MainImg($imgID, $itemID);
            echo json_encode($mainImg->process());
            unset($mainImg, $imgID, $itemID);
        }

        // add option
        if ($func === "Add Option") {
            $optName = htmlspecialchars($data['optName']) ?? "Unnamed option";
            $addOpt = new AddOpt($optName);
            echo json_encode($addOpt->process());
            unset($addOpt, $optName);
        }

        // delete option
        if ($func === "Delete Option") {
            $optID = htmlspecialchars($data['optID']);
            $delOpt = new DelOpt($optID);
            echo json_encode($delOpt->process());
            unset($delOpt, $optID);
        }

        // get option
        if ($func === "Get Option") {
            $optID = htmlspecialchars($data['optID']) ?? 0;
            $getOpt = new GetOpt($optID);
            echo json_encode($getOpt->returnData());
            unset($optID, $getOpt);
        }

        // update option
        if ($func === "Update Option") {
            $optID = htmlspecialchars($data['optID']) ?? 0;
            $optName = htmlspecialchars($data['optName']) ?? "Unnamed option";
            $updOpt = new UpdOpt($optName, $optID);
            echo json_encode($updOpt->process());
            unset($updOpt, $optName, $optID);
        }

        // update option value
        if ($func === "Add Option Value") {
            $optID = htmlspecialchars($data['optID']) ?? 0;
            $valName = htmlspecialchars($data['valName']) ?? "Unnamed option value";
            $addOptVal = new AddOptVal($valName, $optID);
            echo json_encode($addOptVal->process());
            unset($addOptVal, $valName, $optID);
        }

        // delete option value
        if ($func === "Delete Option Value") {
            $valID = htmlspecialchars($data['valID']);
            $del = new DelOptVal($valID);
            echo json_encode($del->process());
            unset($del, $valID);
        }

        // get option value
        if ($func === "Get Option Value") {
            $valID = htmlspecialchars($data['valID']) ?? 0;
            $valData = new GetOptVal($valID);
            echo json_encode($valData->returnData());
            unset($valID, $valData);
        }

        // update option value
        if ($func === "Update Option Value") {
            $valID = htmlspecialchars($data['valID']) ?? 0;
            $valName = htmlspecialchars($data['valName']) ?? "Unnamed option value";
            $updOptVal = new UpdOptVal($valName, $valID);
            echo json_encode($updOptVal->process());
            unset($updOptVal, $valName, $valID);
        }

        // get option, values with associations
        if ($func === "Get Options Assoc") {
            $itemID = htmlspecialchars($data['itemID']) ?? 0;
            $getAssocList = new GetAssocList($itemID);
            echo json_encode($getAssocList->returnData());
            unset($assoc);
        }

        // add option assoc
        if ($func === "Add Option Assoc") {
            $optID = htmlspecialchars($data['optID']) ?? 0;
            $itemID = htmlspecialchars($data['itemID']) ?? 0;
            $addOptAssoc = new AddOptAssoc($optID, $itemID);
            echo json_encode($addOptAssoc->process());
            unset($addOptAssoc, $optID, $itemID);
        }

        // delete option assoc
        if ($func === "Remove Option Assoc") {
            $optID = htmlspecialchars($data['optID']) ?? 0;
            $itemID = htmlspecialchars($data['itemID']) ?? 0;
            $rmvOptAssoc = new RmvOptAssoc($optID, $itemID);
            echo json_encode($rmvOptAssoc->process());
            unset($rmvOptAssoc, $optID, $itemID);
        }

        // add option value assoc
        if ($func === "Add Option Value Assoc") {
            $valID = htmlspecialchars($data['valID']) ?? 0;
            $itemID = htmlspecialchars($data['itemID']) ?? 0;
            $addOptValAssoc = new AddOptValAssoc($valID, $itemID);
            echo json_encode($addOptValAssoc->process());
            unset($addOptValAssoc, $valID, $itemID);
        }

        // delete option value assoc
        if ($func === "Remove Option Value Assoc") {
            $valID = htmlspecialchars($data['valID']) ?? 0;
            $itemID = htmlspecialchars($data['itemID']) ?? 0;
            $rmvOptValAssoc = new RmvOptValAssoc($valID, $itemID);
            echo json_encode($rmvOptValAssoc->process());
            unset($rmvOptValAssoc, $valID, $itemID);
        }

        // get orders
        if ($func === "Get Orders") {
            $getOrders = new GetOrders();
            echo json_encode($getOrders->returnData());
            unset($getOrders);
        }

        // get admins
        if ($func === "Get Admins") {
            $getAdmins = new GetAdmins();
            echo json_encode($getAdmins->returnData());
            unset($getAdmins);
        }

        // add admin
        if ($func === "Add Admin") {
            $adminName = htmlspecialchars($data['adminName']) ?? "Unnamed admin";
            $adminEmail = htmlspecialchars($data['adminEmail']) ?? "";
            $adminPwd = htmlspecialchars($data['adminPwd']) ?? "";
            $addAdmin = new AddAdmin($adminName,  $adminEmail, $adminPwd);
            echo json_encode($addAdmin->process());
            unset($addOpt, $adminName,  $adminEmail, $adminPwd);
        }

        // delete admin
        if ($func === "Delete Admin") {
            $adminID = htmlspecialchars($data['adminID']);
            $delAdmin = new DelAdmin($adminID);
            echo json_encode($delAdmin->process());
            unset($delAdmin, $adminID);
        }

        // get admin
        if ($func === "Get Admin") {
            $adminID = htmlspecialchars($data['adminID']) ?? 0;
            $getAdmin = new GetAdmin($adminID);
            echo json_encode($getAdmin->returnData());
            unset($getAdmin);
        }

        // update admin
        if ($func === "Update Admin") {
            $adminID = htmlspecialchars($data['adminID']) ?? 0;
            $adminName = htmlspecialchars($data['adminName']) ?? "Unnamed admin";
            $adminEmail = htmlspecialchars($data['adminEmail']) ?? "";
            $adminPwd = htmlspecialchars($data['adminPwd']) ?? "";
            $updAdmin = new UpdAdmin($adminName, $adminEmail, $adminPwd, $adminID);
            echo json_encode($updAdmin->process());
            unset($updAdmin, $adminName, $adminEmail, $adminPwd, $adminID);
        }

        // get paypal
        if ($func === "Get PayPal") {
            $getPayPal = new GetPayPal();
            echo json_encode($getPayPal->returnData());
            unset($getPayPal);
        }

        // update paypal
        if ($func === "Update PayPal") {
            $payPalEmail = htmlspecialchars($data['payPalEmail']) ?? "";
            $updPayPal = new UpdPayPal($payPalEmail);
            echo json_encode($updPayPal->process());
            unset($updPayPal, $payPalEmail);
        }

        // get delivery price
        if ($func === "Get Delivery Price") {
            $getDelPrice = new GetDelPrice();
            echo json_encode($getDelPrice->returnData());
            unset($getDelPrice);
        }

        // update delivery price
        if ($func === "Update Delivery Price") {
            $delPrice = htmlspecialchars($data['delPrice']) ?? "";
            $updDelPrice = new UpdDelPrice($delPrice);
            echo json_encode($updDelPrice->process());
            unset($updDelPrice, $delPrice);
        }

        // get login data
        if ($func === "Login") {
            $loginEmail = htmlspecialchars($data['loginEmail']) ?? "";
            $loginPwd = htmlspecialchars($data['loginPwd']) ?? "";
            $getLogin = new GetLogin($loginEmail, $loginPwd);
            echo json_encode($getLogin->returnData());
            unset($getLogin);
        }
    }
}

if ($_FILES && $_POST) {
    $func = htmlspecialchars($_POST['func']);
    if ($func) {

        // Upload image
        if ($func === "Upload Image") {
            $itemID = htmlspecialchars($_POST['itemID']);
            $altTag = htmlspecialchars($_POST['altTag']);
            $caption = htmlspecialchars($_POST['caption']);

            if (move_uploaded_file($_FILES["image"]["tmp_name"], "tempImages/".$_FILES['image']['name'])) {
                $uplImg = new UplImg($_FILES['image']['name'], $_POST['itemID'], $_POST['altTag'], $_POST['caption']);
                echo $uplImg->process();
                unset($uplImg, $itemID, $altTag, $caption);
            }
        }
    }
}


