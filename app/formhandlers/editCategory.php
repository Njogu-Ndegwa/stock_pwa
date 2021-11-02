<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\Category;

if (!empty($_POST['category_name']) && !empty($_POST['category_id'])) {
  $Category = new Category();

  $categoryName = $Category->sanitiseInput($_POST['category_name']);

  $categoryID = $Category->sanitiseInput($_POST['category_id']);

  $addCategoryResponse = $Category->editCategory($categoryID, $categoryName);

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
