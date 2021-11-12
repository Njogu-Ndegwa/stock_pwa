<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\PurchaseOrder;

if (!empty($_POST['purchase_order_id'])) {

  $PurchaseOrder = new PurchaseOrder();

  $purchaseOrderID = $PurchaseOrder->sanitiseInput($_POST['purchase_order_id']);

  $deletePurchaseOrderResponse = $PurchaseOrder->deletePurchaseOrder($purchaseOrderID);

  if ($deletePurchaseOrderResponse['response'] == '200') {

    $_SESSION['success'] = "Purchase Order has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete Purchase Order Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete the Purchase Order in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
