<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Purchase;

if (!empty($_POST['vendor_name']) && !empty($_POST['item']) && !empty($_POST['amount'])) {
  $Purchase = new Purchase();
  $vendorName = $Purchase->sanitiseInput($_POST['vendor_name']);

  $recordDate = $Purchase->sanitiseInput($_POST['record_date']);

  $dueDate = $Purchase->sanitiseInput($_POST['due_date']);

  $quotationReference = $Purchase->sanitiseInput($_POST['quotation_reference']);

  $quotationDate = $Purchase->sanitiseInput($_POST['quotation_date']);

  $purchaseDescription = $Purchase->sanitiseInput($_POST['purchase_description']);

  $purchaseStatus = $Purchase->sanitiseInput($_POST['purchase_status']);

  $item = $Purchase->sanitiseInput($_POST['item']);

  $unitCost = $Purchase->sanitiseInput($_POST['unit_cost']);

  $qty = $Purchase->sanitiseInput($_POST['qty']);

  $amount = $Purchase->sanitiseInput($_POST['amount']);

  $termsConditions = $Purchase->sanitiseInput($_POST['terms_and_conditions']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $addPurchaseResponse = $Purchase->addPurchase($vendorName, $item, $recordDate, $dueDate, $quotationReference, $quotationDate, $purchaseDescription, $qty, $amount, $termsConditions, $unitCost,   $purchaseStatus, $userID);
  if ($addPurchaseResponse['response'] == '200') {
    $_SESSION['success'] = "New purchase has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new purchase";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a Purchase are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
