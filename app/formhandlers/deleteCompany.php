<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\SuperUser;

if (!empty($_POST['company_id'])) {

  $SuperUser = new SuperUser();

  $companyID = $SuperUser->sanitiseInput($_POST['company_id']);

  $deleteCompanyResponse = $SuperUser->deleteCompany($companyID);

  if ($deleteCompanyResponse['response'] == '200') {

    $_SESSION['success'] = "Company has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete company. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete a company in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
