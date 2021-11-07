<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Vendor;

if (!empty($_POST['vendor_name']) && !empty($_POST['vendor_id']) && !empty($_POST['vendor_email']) && !empty($_POST['vendor_mobile'])) {
  $Vendor = new Vendor();

  $vendorName = $Vendor->sanitiseInput($_POST['vendor_name']);

  $vendorID = $Vendor->sanitiseInput($_POST['vendor_id']);

  $vendorEmail = $Vendor->sanitiseInput($_POST['vendor_email']);

  $vendorPhone = $Vendor->sanitiseInput($_POST['vendor_mobile']);

  $vendorDescription = $Vendor->sanitiseInput($_POST['vendor_description']);

  $updateTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

  $addVendorResponse = $Vendor->editVendor($vendorID, $vendorName, $vendorEmail, $vendorPhone, $vendorDescription, $updateTime);

  if ($addVendorResponse['response'] == '200') {
    $_SESSION['success'] = "Vendor has been edited in the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit the vendor";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit vendor are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
