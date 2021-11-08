<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Customer;

if (!empty($_POST['customer_id']) && !empty($_POST['customer_name']) && !empty($_POST['credit_limit']) && !empty($_POST['contact_number']) && !empty($_POST['location_id']) && !empty($_POST['company_id']) && !empty($_POST['contact_person_name']) && !empty($_POST['contact_person_email'])) {
  $Customer = new Customer();

  $customerName = $Customer->sanitiseInput($_POST['customer_name']);

  $creditLimit = $Customer->sanitiseInput($_POST['credit_limit']);

  $contactNumber = $Customer->sanitiseInput($_POST['contact_number']);

  $customerID = $Customer->sanitiseInput($_POST['customer_id']);

  $locationID = $Customer->sanitiseInput($_POST['location_id']);

  $companyID = $Customer->sanitiseInput($_POST['company_id']);

  $contactPersonName = $Customer->sanitiseInput($_POST['contact_person_name']);

  $contactPersonEmail = $Customer->sanitiseInput($_POST['contact_person_email']);

  $addCustomerResponse = $Customer->editCustomer($customerID, $customerName, $creditLimit, $contactNumber, $locationID, $companyID, $contactPersonName, $contactPersonEmail);

  if ($addCustomerResponse['response'] == '200') {
    $_SESSION['success'] = "Customer has been edited";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit the customer";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit customer are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
