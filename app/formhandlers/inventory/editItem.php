<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Material;

if (isset($_POST['item_id']) && isset($_POST['item_name']) && isset($_POST['quantity']) && isset($_POST['minimum_threshold'])  && isset($_POST['maximum_threshold']) && isset($_POST['standard_cost'])) {
  $Material = new Material();

  $itemID = $Material->sanitiseInput($_POST['item_id']);

  $itemName = $Material->sanitiseInput($_POST['item_name']);

  $itemType = $Material->sanitiseInput($_POST['item_type']);

  $itemDescription = $Material->sanitiseInput($_POST['item_description']);

  $serialNumber = $Material->sanitiseInput($_POST['serial_number']);

  $itemCode = NULL;

  $itemQuantity = 0;

  $minThreshold = 0;

  $maxThreshold = 0;

  $unitCost = 0;

  if (isset($_POST['item_code'])) {
    if (isset($_post['vendor_company'])) {
      $_SESSION['error'] = "Required input to add 'Powder' item is missing";
      header("Location:". $_SERVER['HTTP_REFERER']);
      exit();
    }
    $itemCode =  $Material->sanitiseInput($_POST['item_code']);
  }

  if (!isset($_POST['quantity'])) {
    $itemQuantity = $Material->sanitiseInput($_POST['quantity']);
  }


  if (!isset($_POST['minimum_threshold'])) {
    $minThreshold = $Material->sanitiseInput($_POST['minimum_threshold']);
  }

  if (!isset($_POST['maximum_threshold'])) {
    $maxThreshold = $Material->sanitiseInput($_POST['maximum_threshold']);
  }

  $standardCost = $Material->sanitiseInput($_POST['standard_cost']);

  $editItemResponse = $Material->editItem($itemID, $itemType, $itemName, $itemCode, $itemDescription, $itemQuantity, $maxThreshold, $minThreshold, $standardCost, $serialNumber);

  if ($editItemResponse['response'] == '200') {
    $_SESSION['success'] = "Item has been edited successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit item";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit an item missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
