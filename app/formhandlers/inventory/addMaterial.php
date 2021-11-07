<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Material;

if (!empty($_POST['item_name']) && !empty($_POST['item_code']) && !empty($_POST['quantity']) && !empty($_POST['image_url'])  && !empty($_POST['minimum_threshold'])  && !empty($_POST['maximum_threshold']) && !empty($_POST['pricing'])) {
  $Material = new Material();

  $itemName = $Material->sanitiseInput($_POST['item_name']);

  $itemCode = $Material->sanitiseInput($_POST['item_code'])." ".$Material->sanitiseInput($_POST['vendor_company']);

  $itemQuantity = $Material->sanitiseInput($_POST['quantity']);

  $itemImage = $Material->sanitiseInput($_POST['image_url']);

  $itemType = $Material->sanitiseInput($_POST['item_type']);

  $itemMinThreshold = $Material->sanitiseInput($_POST['minimum_threshold']);

  $itemMaxThreshold = $Material->sanitiseInput($_POST['maximum_threshold']);

  $itemPricing = $Material->sanitiseInput($_POST['pricing']);

  $serialNumber = $Material->sanitiseInput($_POST['serial_number']);

  $addMaterialResponse = $Material->addMaterial($itemName, $itemCode, $itemQuantity, $itemImage, $itemMinThreshold, $itemMaxThreshold, $itemType, $serialNumber, $itemPricing);

  if ($addMaterialResponse['response'] == '200') {
    $_SESSION['success'] = "New item has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new item";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add an item missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
