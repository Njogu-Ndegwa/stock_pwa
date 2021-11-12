<?php

namespace app;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class Payment extends Database
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
     * Get all the payments present
     */
    public function getPayment()
    {
        $getPaymentSQL = "SELECT * FROM `payments`";

        return $this->selectSQLStatement($getPaymentSQL, $this->DBConnection);
    }

    /*
     * Add Payments
     */
    public function addPayment(String $voucherNumber, String $paymentDate, String $paymentFrom, String $paymentTo, int $paymentAmount, int $tdsDeducted, String $paymentMode, String $paymentDescription, String $createdBy)
    {
        $addPaymentSQL = "INSERT INTO `payments`(voucher_number, payment_date, payment_from, payment_to, payment_amount, tds_deducted, payment_mode, payment_description, created_by) VALUES ('$voucherNumber', '$paymentDate', '$paymentFrom',  '$paymentTo', '$paymentAmount', '$tdsDeducted', '$paymentMode', '$paymentDescription', '$createdBy')";

        return $this->insertSQLStatement($addPaymentSQL, $this->DBConnection);
    }

    /**
     * Edit Payments
     */
    public function editPayment(String $voucherNumber, String $paymentAmount, String $paymentTo, String $paymentId, String $updatedBy, String $updateTime)
    {
        $updatePaymentSQL = "UPDATE `payments` SET voucher_number = '$voucherNumber', payment_amount = '$paymentAmount', payment_to = '$paymentTo', updated_by = '$updatedBy', updated_at = '$updateTime' WHERE payment_id = '$paymentID'";

        return $this->updateSQLStatement($updatePaymentSQL, $this->DBConnection);
    }

    /**
     * Delete Payments.
     */
    public function deletePayment(String $paymentID)
    {
        $deletePaymentSQL = "DELETE FROM `payments` WHERE payment_id = '$paymentID'";

        return $this->deleteSQLStatement($deletePaymentSQL, $this->DBConnection);
    }

}
