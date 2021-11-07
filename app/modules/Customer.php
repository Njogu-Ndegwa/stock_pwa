<?php

namespace app;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class Customer extends Database
{
    private $DBConnection;

    public function __construct()
    {
        $connectionAttempt = $this->dbConnect();
        if ($connectionAttempt['response'] == '200') {
            $this->DBConnection = $connectionAttempt['data'];
        } else {
            Logger::logToFile('Error', $connectionAttempt['message']);
            throw new \Exception("Error experienced connecting the Customer module to the database", 1);
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
     * Get all the companies present
     */
    public function getCustomers()
    {
        $getCustomersSQL = "SELECT * FROM `customers`";

        return $this->selectSQLStatement($getCustomersSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addCustomer(String $customerName, String $creditLimit, String $contactNumber)
    {
        $addCustomerSQL = "INSERT INTO `customers`(customer_name, credit_limit, contact_number) VALUES ('$customerName', '$creditLimit', '$contactNumber')";

        return $this->insertSQLStatement($addCustomerSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editCustomer(String $customerID, String $customerName, String $creditLimit, String $contactNumber)
    {
        $updateCustomerSQL = "UPDATE `customers` SET customer_name = '$customerName', credit_limit = '$creditLimit', contact_number = '$contactNumber' WHERE customer_id = '$customerID'";

        return $this->updateSQLStatement($updateCustomerSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteCustomer(String $customerID)
    {
        $deleteCustomerSQL = "DELETE FROM `customers` WHERE customer_id = '$customerID'";

        return $this->deleteSQLStatement($deleteCustomerSQL, $this->DBConnection);
    }

}
