<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Warehouse;

if (!empty($_POST['location_id']) && !empty($_POST['warehouse_name']) && !empty($_POST['warehouse_description']) && !empty($_POST['warehouse_status'])) {
  $Warehouse = new Warehouse();

  $warehouseName = $Warehouse->sanitiseInput($_POST['warehouse_name']);

  $warehouseDescription = $Warehouse->sanitiseInput($_POST['warehouse_description']);

  $warehouseStatus = $Warehouse->sanitiseInput($_POST['warehouse_status']);

  $locationID = $Warehouse->sanitiseInput($_POST['location_id']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $addWarehouseResponse = $Warehouse->addWarehouse($warehouseName, $warehouseDescription, $warehouseStatus, $locationID, $userID);

  if ($addWarehouseResponse['response'] == '200') {
    $_SESSION['success'] = "New warehouse has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new warehouse";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a location are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
