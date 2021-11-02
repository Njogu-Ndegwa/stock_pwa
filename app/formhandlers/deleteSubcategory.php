<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\Subcategory;

if (!empty($_POST['subcategory_id'])) {

  $Subcategory = new Subcategory();

  $subcategoryID = $Subcategory->sanitiseInput($_POST['subcategory_id']);

  $deleteSubcategoryResponse = $Subcategory->deleteSubcategory($subcategoryID);

  if ($deleteSubcategoryResponse['response'] == '200') {

    $_SESSION['success'] = "Subcategory has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete subcategory. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete a subcategory in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
