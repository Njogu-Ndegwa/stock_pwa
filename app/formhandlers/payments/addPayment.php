<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Payment;

if (!empty($_POST['voucher_number']) && !empty($_POST['payment_amount']) && !empty($_POST['payment_to'])) {
  $Payment = new Payment();

  $voucherNumber = $Payment->sanitiseInput($_POST['voucher_number']);

  $paymentDate = $Payment->sanitiseInput($_POST['payment_date']);

  $paymentFrom = $Payment->sanitiseInput($_POST['payment_from']);

  $paymentTo = $Payment->sanitiseInput($_POST['payment_to']);

  $paymentAmount = $Payment->sanitiseInput($_POST['payment_amount']);

  $tdsDeducted = $Payment->sanitiseInput($_POST['tds_deducted']);

  $paymentMode = $Payment->sanitiseInput($_POST['payment_mode']);

  $paymentDescription = $Payment->sanitiseInput($_POST['payment_description']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $addPaymentResponse = $Payment->addPayment($voucherNumber, $paymentDate,  $paymentFrom, $paymentTo, $paymentAmount, $tdsDeducted, $paymentMode, $paymentDescription, $userID);
  print_r($addPaymentResponse);

  if ($addPaymentResponse['response'] == '200') {

    $_SESSION['success'] = "New Payment has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new Payment";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a Payment are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}