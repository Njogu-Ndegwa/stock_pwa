<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\Subcategory;

if (!empty($_POST['subcategory_name']) && !empty($_POST['category_id']) && !empty($_POST['subcategory_id'])) {
  $Subcategory = new Subcategory();

  $subcategoryName = $Subcategory->sanitiseInput($_POST['subcategory_name']);

  $subcategoryID = $Subcategory->sanitiseInput($_POST['subcategory_id']);

  $categoryID = $Subcategory->sanitiseInput($_POST['category_id']);

  $editSubcategoryResponse = $Subcategory->editSubcategory($subcategoryID, $categoryID, $subcategoryName);

  if ($editSubcategoryResponse['response'] == '200') {
    $_SESSION['success'] = "Subcategory has been edited successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['success'] = "Failed to edit subcategory";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit the subcategory are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
