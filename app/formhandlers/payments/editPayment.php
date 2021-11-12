<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Payment;

if (!empty($_POST['voucher_number']) && !empty($_POST['payment_amount']) && !empty($_POST['payment_id']) && !empty($_POST['payment_to'])) {
    $Payment = new Payment();
  
    $voucherNumber = $Payment->sanitiseInput($_POST['voucher_number']);
  
    $paymentAmount = $Payment>sanitiseInput($_POST['payment_amount']);
  
    $paymentTo = $Payment->sanitiseInput($_POST['payment_to']);

    $paymentID = $Expense->sanitiseInput($_POST['purchase_id']);

  $updateTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

  $addPaymentResponse = $Payment->addPayment($voucherNumber, $paymentAmount, $paymentID, $paymentTo, $userID);

  if ($addPaymentResponse['response'] == '200') {
    $_SESSION['success'] = "Payment has been edited in the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit the Payment";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit Payment are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
