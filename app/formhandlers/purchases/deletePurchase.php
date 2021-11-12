<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Purchase;

if (!empty($_POST['purchase_id'])) {

  $Purchase = new Purchase();

  $purchaseID = $Purchase->sanitiseInput($_POST['purchase_id']);

  $deletePurchaseResponse = $Purchase->deletePurchase($purchaseID);

  if ($deletePurchaseResponse['response'] == '200') {

    $_SESSION['success'] = "Purchase has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete purchase. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete the purchase in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
