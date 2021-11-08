<?php
require_once '../../vendor/autoload.php';

use app\Warehouse;

$Warehouse = new Warehouse();

// get the input
$inputInfo = json_decode(file_get_contents('php://input'));

header("Access-Control-Allow-Origin:" .$_ENV['APP_URL']);
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: *");
header("Content-Type:application/json;charset=UTF-8");

if (!empty($inputInfo)) {
  $locationID = $Warehouse->sanitiseInput($inputInfo->location_id);
  echo json_encode($Warehouse->getLocationWarehouses($locationID));
}else {
  echo json_encode("Blank Data");
}
