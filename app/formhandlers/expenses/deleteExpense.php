<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Expense;

if (!empty($_POST['expense_id'])) {

  $Expense = new Expense();

  $expenseID = $Expense->sanitiseInput($_POST['purchase_order_id']);

  $deleteExpenseResponse = $Expense->deletePurchase($expenseID);

  if ($deleteExpenseResponse['response'] == '200') {

    $_SESSION['success'] = "Expense has been deleted";
    header("Expense:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete Expense Error has been logged";
    header("Expense:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete the Expense in system are missing";
  header("Expense:". $_SERVER['HTTP_REFERER']);
  exit();
}
