<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\Vendor;

if (!empty($_POST['vendor_name'])) {
  $Vendor = new Vendor();
  
  $vendorName = $Vendor->sanitiseInput($_POST['vendor_name']);

  $addVendorResponse = $Vendor->addVendor($vendorName);

  if ($addVendorResponse['response'] == '200') {
    $_SESSION['success'] = "New vendor has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new vendor";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a vendor are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
