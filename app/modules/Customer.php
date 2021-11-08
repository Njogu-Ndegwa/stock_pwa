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
        $getCustomersSQL = "SELECT * FROM `customers` INNER JOIN locations ON locations.location_id  = customers.location_id INNER JOIN companies ON companies.company_id  = customers.company_id";

        return $this->selectSQLStatement($getCustomersSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addCustomer(String $customerName, String $creditLimit, String $contactNumber, String $locationID, String $companyID, String $contactPersonName, String $contactPersonEmail)
    {
        $addCustomerSQL = "INSERT INTO `customers`(customer_name, credit_limit, contact_number, location_id, company_id, contact_person_name, contact_person_email) VALUES ('$customerName', '$creditLimit', '$contactNumber', '$locationID', '$companyID', '$contactPersonName', '$contactPersonEmail')";

        return $this->insertSQLStatement($addCustomerSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editCustomer(String $customerID, String $customerName, String $creditLimit, String $contactNumber, String $locationID, String $companyID, String $contactPersonName, String $contactPersonEmail)
    {
        $updateCustomerSQL = "UPDATE `customers` SET customer_name = '$customerName', credit_limit = '$creditLimit', contact_number = '$contactNumber', location_id = '$locationID', company_id = '$companyID', contact_person_name = '$contactPersonName', contact_person_email = '$contactPersonEmail' WHERE customer_id = '$customerID'";

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
