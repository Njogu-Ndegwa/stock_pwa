<?php
require_once '../../vendor/autoload.php';

use app\Vendor;

$inputInfo = json_decode(file_get_contents('php://input'));

if (!empty($inputInfo->vendorName) && !empty($inputInfo->vendorEmail) && !empty($inputInfo->vendorMobile)) {

  $Vendor = new Vendor();

  $vendorName = $Vendor->sanitiseInput($inputInfo->vendorName);

  $vendorEmail = $Vendor->sanitiseInput($inputInfo->vendorEmail);

  $vendorPhone = $Vendor->sanitiseInput($inputInfo->vendorMobile);

  $vendorDescription = $Vendor->sanitiseInput($inputInfo->vendorDescription);

  $addVendorResponse = $Vendor->addVendor($vendorName, $vendorEmail, $vendorPhone, $vendorDescription);

  header("Access-Control-Allow-Origin:" .$_ENV['APP_URL']);
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: *");
  header("Content-Type:application/json;charset=UTF-8");

  echo json_encode($addVendorResponse);

}else {
  $response = array(
    'response' => '500',
    'message' => 'Data required isn\'t present'
  );

  echo json_encode($response);
}
