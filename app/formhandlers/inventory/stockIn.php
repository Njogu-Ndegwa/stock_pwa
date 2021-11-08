<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Material;

if (!empty($_POST['item_name']) && !empty($_POST['item_code'])) {
  $Material = new Material();

  $itemName = $Material->sanitiseInput($_POST['item_name']);

  $itemCode = $Material->sanitiseInput($_POST['item_code']);

  $itemName = $Material->sanitiseInput($_POST['item_name']);

  $vendorID = $Material->sanitiseInput($_POST['vendor_id']);

  $locationID = $Material->sanitiseInput($_POST['location_id']);

  $warehouseID = $Material->sanitiseInput($_POST['warehouse_id']);

  $lpo = $Material->sanitiseInput($_POST['lpo']);

  $invoice = $Material->sanitiseInput($_POST['invoice']);

  $deliveryNoteNo = $Material->sanitiseInput($_POST['delivery_note_no']);

  $pricePerItem = $Material->sanitiseInput($_POST['price_per_item']);

  $costPerItem = $Material->sanitiseInput($_POST['cost_per_item']);

  $minimumThreshold = $Material->sanitiseInput($_POST['minimum_threshold']);

  $maximumThreshold = $Material->sanitiseInput($_POST['maximum_threshold']);

  $vehiclePlate = $Material->sanitiseInput($_POST['vehicle_plate']);

  $startMileage = $Material->sanitiseInput($_POST['start_mileage']);

  $stopMileage = $Material->sanitiseInput($_POST['stop_mileage']);

  $quantity = $Material->sanitiseInput($_POST['quantity']);

  $powder = $Material->sanitiseInput($_POST['powder']);

  $color = $Material->sanitiseInput($_POST['color']);

  $material = $Material->sanitiseInput($_POST['material']);

  $imageURL = $Material->sanitiseInput($_POST['image_url']);

  $stockInItemResponse = $Material->stockIn( $itemName,  $itemCode,  $locationID,  $warehouseID,  $vendorID,  $invoice,  $lpo,  $quantity,  $deliveryNoteNo,  $vehiclePlate,  $startMileage,  $stopMileage,  $powder,  $color ,  $material,   $pricePerItem,  $costPerItem,  $imageURL,  $minimumThreshold,  $maximumThreshold);

  if ($stockInItemResponse['response'] == '200') {
    $_SESSION['success'] = "Stock in recorded";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to stock in item";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }


}else {
  $_SESSION['error'] = "Required input to stock an item missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
