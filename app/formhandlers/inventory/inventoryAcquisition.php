<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Material;

if (!empty($_POST['vendor_id']) && !empty($_POST['customer_id']) && !empty($_POST['item']) && !empty($_POST['quantity']) && !empty($_POST['description']) && !empty($_POST['date'])) {

  $Material = new Material();

  $vendorID = $Material->sanitiseInput($_POST['vendor_id']);

  $customerID = $Material->sanitiseInput($_POST['customer_id']);

  $item = $Material->sanitiseInput($_POST['item']);

  $quantity = $Material->sanitiseInput($_POST['quantity']);

  $description = $Material->sanitiseInput($_POST['description']);

  $date = $Material->sanitiseInput($_POST['date']);

  $acquisitionResponse = $Material->itemAcquisition($vendorID, $customerID, $item, $quantity, $description, $date);

  if ($acquisitionResponse['response'] == '200') {
    $_SESSION['success'] = "Inventory acquisition recorded";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to record inventory acquisition";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to perform inventory acquisiton are empty";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
