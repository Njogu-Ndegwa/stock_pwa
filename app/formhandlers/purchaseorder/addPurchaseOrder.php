<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\PurchaseOrder;

if (!empty($_POST['vendor_name']) && !empty($_POST['item_name']) && !empty($_POST['po_status'])) {
  $PurchaseOrder = new PurchaseOrder();

  $vendorName = $PurchaseOrder->sanitiseInput($_POST['vendor_name']);

  $recordDate = $PurchaseOrder->sanitiseInput($_POST['record_date']);

  $dueDate = $PurchaseOrder->sanitiseInput($_POST['due_date']);

  $quotationReference = $PurchaseOrder->sanitiseInput($_POST['quotation_reference']);

  $quotationDate = $PurchaseOrder->sanitiseInput($_POST['quotation_date']);


  $items = array();

  for ($i=0; $i < count($_POST['item_name']) ; $i++) {
    $rowItem = array(
      'item_name' => $_POST['item_name'][$i],
      'item_description' => $_POST['item_description'][$i],
      'item_quantity' => $_POST['item_quantity'][$i],
      'item_kg' => $_POST['item_kg'][$i],
      'item_unit_cost' => $_POST['item_unit_cost'][$i],
      'item_amount' => $_POST['item_amount'][$i]
    );
    array_push($items, $rowItem);
  }

  $itemsSectionData = json_encode($items);

  $totalAmount = sanitiseInput($_POST['amount']);

  $poStatus = $PurchaseOrder->sanitiseInput($_POST['po_status']);

  $termsConditions = $PurchaseOrder->sanitiseInput($_POST['terms_and_conditions']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $addPurchaseOrderResponse = $PurchaseOrder->addPurchaseOrder($vendorName, $itemsSectionData, $recordDate, $dueDate, $quotationReference, $quotationDate, $termsConditions,  $poStatus, $userID);

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