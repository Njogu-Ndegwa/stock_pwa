<?php

namespace app;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class Expense extends Database
{
    private $DBConnection;

    public function __construct()
    {
        $connectionAttempt = $this->dbConnect();
        if ($connectionAttempt['response'] == '200') {
            $this->DBConnection = $connectionAttempt['data'];
        } else {
            Logger::logToFile('Error', $connectionAttempt['message']);
            throw new \Exception("Error experienced connecting the Warehouse module to the database", 1);
            exit();
        }
    }

    /**
     * Sanitize the input to prevent against SQL injection
     */
    public function sanitiseInput($inputVariable)
    {
        return $this->sanitiseData($inputVariable, $this->DBConnection);
    }

    /**
     * Get all the Expenses present
     */
    public function getExpenses()
    {
        $getExpenseSQL = "SELECT * FROM `expenses`";

        return $this->selectSQLStatement($getExpenseSQL, $this->DBConnection);
    }

    /*
     * Add Payments
     */
    public function addExpense( String $voucherNumber, int $expenseAmount, String $expenseDate, String $paidFrom, int $tax,  String $expenseStatus, String $expenseType, String $expenseDescription, String $expenseDocument, String $expenseMemo, String $createdBy)
    {
        $addExpenseSQL = "INSERT INTO `expenses`(voucher_number, expense_amount, expense_date, expense_description,  paid_from, tax, expense_status, expense_type, document, memo, created_by) VALUES ('$voucherNumber', '$expenseAmount', '$expenseDate', '$expenseDescription', '$paidFrom', '$tax', '$expenseStatus', '$expenseType', '$expenseDocument', '$expenseMemo', '$createdBy')";

        return $this->insertSQLStatement($addExpenseSQL, $this->DBConnection);
    }

    /**
     * Edit Payments
     */
    public function editExpense(String $voucherNumber, String $paymentAmount, String $paymentTo, String $paymentId, String $updatedBy, String $updateTime)
    {
        $updatePaymentSQL = "UPDATE `expenses` SET voucher_number = '$voucherNumber', payment_amount = '$paymentAmount', payment_to = '$paymentTo', updated_by = '$updatedBy', updated_at = '$updateTime' WHERE payment_id = '$paymentID'";

        return $this->updateSQLStatement($updatePaymentSQL, $this->DBConnection);
    }

    /**
     * Delete Payments.
     */
    public function deleteExpense(String $paymentID)
    {
        $deletePaymentSQL = "DELETE FROM `payment` WHERE payment_id = '$paymentID'";

        return $this->deleteSQLStatement($deletePaymentSQL, $this->DBConnection);
    }

}
