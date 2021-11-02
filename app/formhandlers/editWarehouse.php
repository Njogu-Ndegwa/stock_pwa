<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\Warehouse;

if (!empty($_POST['warehouse_name']) && !empty($_POST['location_id']) && !empty($_POST['warehouse_id'])) {
  $Warehouse = new Warehouse();

  $warehouseName = $Warehouse->sanitiseInput($_POST['warehouse_name']);

  $warehouseID = $Warehouse->sanitiseInput($_POST['warehouse_id']);

  $locationID = $Warehouse->sanitiseInput($_POST['location_id']);

  $editWarehouseResponse = $Warehouse->editWarehouse($warehouseID, $locationID, $warehouseName);

  if ($editWarehouseResponse['response'] == '200') {
    $_SESSION['success'] = "Warehouse has been edited successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['success'] = "Failed to edit warehouse";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit the warehouse are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
