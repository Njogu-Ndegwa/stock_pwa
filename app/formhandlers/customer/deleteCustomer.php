<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Customer;

if (!empty($_POST['customer_id'])) {

  $Customer = new Customer();

  $customerID = $Customer->sanitiseInput($_POST['customer_id']);

  $deleteCustomerResponse = $Customer->deleteCustomer($customerID);

  if ($deleteCustomerResponse['response'] == '200') {

    $_SESSION['success'] = "Customer has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete customer. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete the customer in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
