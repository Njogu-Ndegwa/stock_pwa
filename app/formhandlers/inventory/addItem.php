<?php
require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Material;

if (!empty($_POST['item_name']) && !empty($_POST['item_description']) && !empty($_POST['serial_number'])){

  $Material = new Material();

  $itemName = $Material->sanitiseInput($_POST['item_name']);

  $itemType = $Material->sanitiseInput($_POST['item_type']);

  $itemDescription = $Material->sanitiseInput($_POST['item_description']);

  $serialNumber = $Material->sanitiseInput($_POST['serial_number']);

  $itemCode = NULL;

  $itemQuantity = 0;

  $minThreshold = 0;

  $maxThreshold = 0;

  $unitCost = 0;

  $unitPrice = 0;

  if (isset($_POST['item_code'])) {
    if (isset($_post['vendor_company'])) {
      $_SESSION['error'] = "Required input to add 'Powder' item is missing";
      header("Location:". $_SERVER['HTTP_REFERER']);
      exit();
    }
    $itemCode =  $Material->sanitiseInput($_POST['item_code'])." ".$Material->sanitiseInput($_POST['vendor_company']);
  }

  if (!empty($_POST['quantity'])) {
    $itemQuantity = $Material->sanitiseInput($_POST['quantity']);
  }


  if (!empty($_POST['minimum_threshold'])) {
    $minThreshold = $Material->sanitiseInput($_POST['minimum_threshold']);
  }

  if (!empty($_POST['maximum_threshold'])) {
    $maxThreshold = $Material->sanitiseInput($_POST['maximum_threshold']);
  }

  if (!empty($_POST['unit_cost'])) {
    $unitCost = $Material->sanitiseInput($_POST['unit_cost']);
  }

  if (!empty($_POST['unit_price'])) {
    $unitPrice = $Material->sanitiseInput($_POST['unit_price']);
  }

  $addItemResponse = $Material->addItem($itemType, $itemName, $itemCode, $itemDescription, $itemQuantity, $maxThreshold, $minThreshold, $unitCost, $unitCost, $unitPrice, $serialNumber);

  if ($addItemResponse['response'] == '200') {
    $_SESSION['success'] = "Item has been added to inventory";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add item to inventory";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to add an item is missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
