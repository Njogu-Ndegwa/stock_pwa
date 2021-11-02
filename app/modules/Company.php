<?php

namespace app;

/**
 * This is the Company module
 * this handles a company's actions and
 * manipulating its data
 */
class Company extends Database
{
    private $DBConnection;

    public function __construct()
    {
        $connectionAttempt = $this->dbConnect();
        if ($connectionAttempt['response'] == '200') {
            $this->DBConnection = $connectionAttempt['data'];
        } else {
            Logger::logToFile('Error', $connectionAttempt['message']);
            throw new \Exception("Error experienced connecting the Company module to the database", 1);
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
    public function getCompanies()
    {
        $getCompaniesSQL = "SELECT * FROM companies";

        return $this->selectSQLStatement($getCompaniesSQL, $this->DBConnection);
    }
}
