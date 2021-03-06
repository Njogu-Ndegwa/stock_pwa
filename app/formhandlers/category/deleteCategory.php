<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Category;

if (!empty($_POST['category_id'])) {

  $Category = new Category();

  $categoryID = $Category->sanitiseInput($_POST['category_id']);

  $deleteCategoryResponse = $Category->deleteCategory($categoryID);

  if ($deleteCategoryResponse['response'] == '200') {

    $_SESSION['success'] = "Category has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete category. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete the category in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
