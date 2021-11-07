<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Subcategory;

if (!empty($_POST['category_id']) && !empty($_POST['subcategory_name']) && !empty($_POST['subcategory_description']) && !empty($_POST['subcategory_status'])) {
  $Subcategory = new Subcategory();

  $subcategoryName = $Subcategory->sanitiseInput($_POST['subcategory_name']);

  $subcategoryDescription = $Subcategory->sanitiseInput($_POST['subcategory_description']);

  $subcategoryStatus = $Subcategory->sanitiseInput($_POST['subcategory_status']);

  $categoryID = $Subcategory->sanitiseInput($_POST['category_id']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $addSubcategoryResponse = $Subcategory->addSubcategory($subcategoryName, $subcategoryDescription, $subcategoryStatus, $categoryID, $userID);

  if ($addSubcategoryResponse['response'] == '200') {
    $_SESSION['success'] = "New subcategory has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new subcategory";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a subcategory are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
