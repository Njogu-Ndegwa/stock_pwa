<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\PurchaseOrder;

if (!empty($_POST['vendor_name']) && !empty($_POST['purchase_order_id']) && !empty($_POST['item']) && !empty($_POST['status'])) {
  $PurchaseOrder = new PurchaseOrder();

  $vendorName = $PurchaseOrder->sanitiseInput($_POST['vendor_name']);

  $item = $PurchaseOrder>sanitiseInput($_POST['item']);

  $poStatus = $PurchaseOrder->sanitiseInput($_POST['status']);

  $purchaseOrderID = $PurchaseOrder->sanitiseInput($_POST['purchase_id']);

  $updateTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

  $addPurchaseOrderResponse = $PurchaseOrder->editPurchaseOrder($purchaseOrderID, $poStatus, $item, $vendorName, $userID);

  if ($addPurchaseOrderResponse['response'] == '200') {
    $_SESSION['success'] = "Purchase Order has been edited in the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit the purchase Order";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit purchase Order are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
