<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Vendor;

if (!empty($_POST['vendor_name']) && !empty($_POST['vendor_email']) && !empty($_POST['vendor_mobile'])) {
  $Vendor = new Vendor();

  $vendorName = $Vendor->sanitiseInput($_POST['vendor_name']);

  $vendorEmail = $Vendor->sanitiseInput($_POST['vendor_email']);

  $vendorPhone = $Vendor->sanitiseInput($_POST['vendor_mobile']);

  $vendorDescription = $Vendor->sanitiseInput($_POST['vendor_description']);

  $addVendorResponse = $Vendor->addVendor($vendorName, $vendorEmail, $vendorPhone, $vendorDescription);

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
