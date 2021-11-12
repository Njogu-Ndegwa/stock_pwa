<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Purchase;

if (!empty($_POST['vendor_name']) && !empty($_POST['purchase_id']) && !empty($_POST['item']) && !empty($_POST['status'])) {
  $Purchase = new Purchase();

  $vendorName = $Purchase->sanitiseInput($_POST['vendor_name']);

  $item = $Purchase>sanitiseInput($_POST['item']);

  $status = $Purchase->sanitiseInput($_POST['status']);

  $purchaseID = $Purchase->sanitiseInput($_POST['purchase_id']);

  $updateTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

  $addPurchaseResponse = $Purchase->editPurchase($purchaseID, $status, $item, $vendorName, $userID);

  if ($addPurchaseResponse['response'] == '200') {
    $_SESSION['success'] = "Purchase has been edited in the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit the purchase";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit purchase are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
