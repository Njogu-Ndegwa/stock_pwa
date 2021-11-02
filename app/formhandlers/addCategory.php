<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\Category;

if (!empty($_POST['category_name'])) {
  $Category = new Category();

  $categoryName = $Category->sanitiseInput($_POST['category_name']);

  $addCategoryResponse = $Category->addCategory($categoryName);

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
