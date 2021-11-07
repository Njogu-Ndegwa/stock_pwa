<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Warehouse;

if (!empty($_POST['location_id']) && !empty($_POST['warehouse_id']) && !empty($_POST['warehouse_description']) && !empty($_POST['warehouse_status']) && !empty($_POST['warehouse_name'])) {
  $Warehouse = new Warehouse();

  $warehouseName = $Warehouse->sanitiseInput($_POST['warehouse_name']);

  $warehouseStatus = $Warehouse->sanitiseInput($_POST['warehouse_status']);

  $warehouseDescription = $Warehouse->sanitiseInput($_POST['warehouse_description']);

  $warehouseID = $Warehouse->sanitiseInput($_POST['warehouse_id']);

  $locationID = $Warehouse->sanitiseInput($_POST['location_id']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $updateTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

  $editWarehouseResponse = $Warehouse->editWarehouse($warehouseID, $locationID, $warehouseName, $warehouseDescription, $warehouseStatus, $userID, $updateTime);

  if ($editWarehouseResponse['response'] == '200') {
    $_SESSION['success'] = "Warehouse has been edited successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit warehouse";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit the warehouse are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
