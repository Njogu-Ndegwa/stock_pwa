<?php
require_once '../../vendor/autoload.php';

use app\Material;

$Material = new Material();

// get the input
$inputInfo = json_decode(file_get_contents('php://input'));

header("Access-Control-Allow-Origin:" .$_ENV['APP_URL']);
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: *");
header("Content-Type:application/json;charset=UTF-8");

if (!empty($inputInfo)) {
  $itemID = $Material->sanitiseInput($inputInfo->item_id);

  $addedQuantity = $Material->sanitiseInput($inputInfo->quantity);

  $getItemInfoResponse = $Material->getItemById($itemID);

  $existingQuantity = $getItemInfoResponse['data'][0]['quantity'];

  $maxThreshold = $getItemInfoResponse['data'][0]['maximum_threshold'];

  $proposedQuantity = $existingQuantity + $addedQuantity;

  if ($proposedQuantity > $maxThreshold) {
    $response = array(
      'message' => 'More',
    );
  }else {
    $response = array(
      'message' => 'Less', 
    );
  }
  echo json_encode($response);
}else {
  echo json_encode("Blank Data");
}
