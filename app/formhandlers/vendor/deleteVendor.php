<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Vendor;

if (!empty($_POST['vendor_id'])) {

  $Vendor = new Vendor();

  $vendorID = $Vendor->sanitiseInput($_POST['vendor_id']);

  $deleteVendorResponse = $Vendor->deleteCategory($vendorID);

  if ($deleteVendorResponse['response'] == '200') {

    $_SESSION['success'] = "Vendor has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete vendor. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete a vendor in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
