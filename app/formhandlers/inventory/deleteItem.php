<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Material;

if (!empty($_POST['item_id'])) {

  $Material = new Material();

  $itemID = $Material->sanitiseInput($_POST['item_id']);

  $deleteMaterialResponse = $Material->deleteMaterial($itemID);

  if ($deleteMaterialResponse['response'] == '200') {

    $_SESSION['success'] = "Item has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete item. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete the item in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
