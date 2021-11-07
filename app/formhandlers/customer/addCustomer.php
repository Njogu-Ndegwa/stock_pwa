<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Customer;

if (!empty($_POST['customer_name']) && !empty($_POST['credit_limit']) && !empty($_POST['contact_number'])) {
  $Customer = new Customer();

  $customerName = $Customer->sanitiseInput($_POST['customer_name']);

  $creditLimit = $Customer->sanitiseInput($_POST['credit_limit']);

  $contactNumber = $Customer->sanitiseInput($_POST['contact_number']);

  $addCustomerResponse = $Customer->addCustomer($customerName, $creditLimit, $contactNumber);

  if ($addCustomerResponse['response'] == '200') {
    $_SESSION['success'] = "New customer has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new customer";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a customer are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
