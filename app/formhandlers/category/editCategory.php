<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Category;

if (!empty($_POST['category_name']) && !empty($_POST['category_id']) && !empty($_POST['category_description']) && !empty($_POST['category_status'])) {
  $Category = new Category();

  $categoryName = $Category->sanitiseInput($_POST['category_name']);

  $categoryStatus = $Category->sanitiseInput($_POST['category_status']);

  $categoryDescription = $Category->sanitiseInput($_POST['category_description']);

  $categoryID = $Category->sanitiseInput($_POST['category_id']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $updateTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

  $addCategoryResponse = $Category->editCategory($categoryID, $categoryName, $categoryDescription, $categoryStatus, $userID, $updateTime);

  if ($addCategoryResponse['response'] == '200') {
    $_SESSION['success'] = "Category has been edited in the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit the category";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit category are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
