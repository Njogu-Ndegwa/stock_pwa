<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Category;

if (!empty($_POST['category_name']) && !empty($_POST['category_description']) && !empty($_POST['category_status'])) {
  $Category = new Category();

  $categoryName = $Category->sanitiseInput($_POST['category_name']);

  $categoryDescription = $Category->sanitiseInput($_POST['category_description']);

  $categoryStatus = $Category->sanitiseInput($_POST['category_status']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $addCategoryResponse = $Category->addCategory($categoryName, $categoryDescription, $categoryStatus, $userID);

  if ($addCategoryResponse['response'] == '200') {
    $_SESSION['success'] = "New category has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new category";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a location are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
