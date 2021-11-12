<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\PurchaseOrder;

if (!empty($_POST['vendor_name']) && !empty($_POST['item']) && !empty($_POST['po_status'])) {
  $PurchaseOrder = new PurchaseOrder();

  $vendorName = $PurchaseOrder->sanitiseInput($_POST['vendor_name']);

  $recordDate = $PurchaseOrder->sanitiseInput($_POST['record_date']);

  $dueDate = $PurchaseOrder->sanitiseInput($_POST['due_date']);

  $quotationReference = $PurchaseOrder->sanitiseInput($_POST['quotation_reference']);

  $quotationDate = $PurchaseOrder->sanitiseInput($_POST['quotation_date']);

  $itemDescription = $PurchaseOrder->sanitiseInput($_POST['item_description']);

  $poStatus = $PurchaseOrder->sanitiseInput($_POST['po_status']);

  $item = $PurchaseOrder->sanitiseInput($_POST['item']);

  $unitCost = $PurchaseOrder->sanitiseInput($_POST['unit_cost']);

  $qty = $PurchaseOrder->sanitiseInput($_POST['qty']);

  $amount = $PurchaseOrder->sanitiseInput($_POST['amount']);

  $termsConditions = $PurchaseOrder->sanitiseInput($_POST['terms_and_conditions']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $addPurchaseOrderResponse = $PurchaseOrder->addPurchaseOrder($vendorName, $item, $recordDate, $dueDate, $quotationReference, $quotationDate, $itemDescription, $qty, $amount, $termsConditions, $unitCost,   $poStatus, $userID);

  if ($addPurchaseOrderResponse['response'] == '200') {
    $_SESSION['success'] = "New purchase Order has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new purchase Order";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a location are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}