<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Expense;

if (!empty($_POST['voucher_number']) && !empty($_POST['expense_amount'])) {
  $Expense = new Expense();

  $voucherNumber = $Expense->sanitiseInput($_POST['voucher_number']);

  $expenseAmount = $Expense->sanitiseInput($_POST['expense_amount']);

  $expenseDescription = $Expense->sanitiseInput($_POST['expense_description']);

  $expenseDate = $Expense->sanitiseInput($_POST['expense_date']);

  $paidFrom = $Expense->sanitiseInput($_POST['paid_from']);

  $tax = $Expense->sanitiseInput($_POST['tax']);

  $expenseStatus = $Expense->sanitiseInput($_POST['expense_status']);

  $expenseType= $Expense->sanitiseInput($_POST['expense_type']);

  $expenseDocument= $Expense->sanitiseInput($_POST['document']);

  $expenseMemo = $Expense->sanitiseInput($_POST['memo']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $addExpenseResponse = $Expense->addExpense($voucherNumber, $expenseAmount, $expenseDate, $paidFrom, $tax, $expenseStatus, $expenseDescription, $expenseType, $expenseDocument,  $expenseMemo, $userID);

  print_r($addExpenseResponse);

  if ($addExpenseResponse['response'] == '200') {
    $_SESSION['success'] = "New Expense has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new Expense";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add an Expense  are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}