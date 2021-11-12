<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Payment;

if (!empty($_POST['payment_id'])) {

  $Payment = new Payment();

  $paymentID = $Payment->sanitiseInput($_POST['payment_id']);

  $deletePaymentResponse = $Payment->deletePayment($paymentID);

  if ($deletePaymentResponse['response'] == '200') {

    $_SESSION['success'] = "Payment has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete Payment Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete the Payment in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
