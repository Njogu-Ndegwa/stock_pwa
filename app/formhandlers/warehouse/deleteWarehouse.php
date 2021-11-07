<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Warehouse;

if (!empty($_POST['warehouse_id'])) {

  $Warehouse = new Warehouse();

  $warehouseID = $Warehouse->sanitiseInput($_POST['warehouse_id']);

  $deleteWarehouseResponse = $Warehouse->deleteWarehouse($warehouseID);

  if ($deleteWarehouseResponse['response'] == '200') {

    $_SESSION['success'] = "Warehouse has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete warehouse. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete a company in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
