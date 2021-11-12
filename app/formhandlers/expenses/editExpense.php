<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Expense;

if (!empty($_POST['voucher_number']) && !empty($_POST['expense_id']) && !empty($_POST['expense']) && !empty($_POST['amount'])) {
  $Expense = new Expense();

  $Expense = new Expense();

  $voucherNumber = $Expense->sanitiseInput($_POST['voucher_number']);

  $expense = $Expense>sanitiseInput($_POST['expense']);

  $amount = $Expense->sanitiseInput($_POST['amount']);

  $expenseID = $Expense->sanitiseInput($_POST['purchase_id']);

  $updateTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

  $addExpenseResponse = $Expense->editPurchase($expenseID, $voucherNumber, $expense, $updateTime , $userID);

  if ($addExpenseResponse['response'] == '200') {
    $_SESSION['success'] = "Expense has been edited in the system successfuly";
    header("Expense:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to edit the Expense";
    header("Expense:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit Expense are missing";
  header("Expense:". $_SERVER['HTTP_REFERER']);
  exit();
}
