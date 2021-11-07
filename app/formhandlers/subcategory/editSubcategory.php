<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Subcategory;

if (!empty($_POST['subcategory_name']) && !empty($_POST['category_id']) && !empty($_POST['subcategory_id']) && !empty($_POST['subcategory_description']) && !empty($_POST['subcategory_status'])) {
  $Subcategory = new Subcategory();

  $subcategoryName = $Subcategory->sanitiseInput($_POST['subcategory_name']);

  $subcategoryID = $Subcategory->sanitiseInput($_POST['subcategory_id']);

  $subcategoryStatus = $Subcategory->sanitiseInput($_POST['subcategory_status']);

  $subcategoryDescription = $Subcategory->sanitiseInput($_POST['subcategory_description']);

  $categoryID = $Subcategory->sanitiseInput($_POST['category_id']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $updateTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

  $editSubcategoryResponse = $Subcategory->editSubcategory($subcategoryID, $categoryID, $subcategoryName, $subcategoryStatus, $subcategoryDescription, $userID, $updateTime);

  if ($editSubcategoryResponse['response'] == '200') {
    $_SESSION['success'] = "Subcategory has been edited successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit subcategory";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit the subcategory are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
