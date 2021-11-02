<?php

namespace app;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class Vendor extends Database
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
     * Get all the companies present
     */
    public function getVendors()
    {
        $getVendorsSQL = "SELECT * FROM `vendors`";

        return $this->selectSQLStatement($getVendorsSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addVendor(String $vendorName)
    {
        $addVendorSQL = "INSERT INTO `vendors`(vendor_name) VALUES ('$vendorName')";

        return $this->insertSQLStatement($addVendorSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editVendor(String $vendorID, String $vendorName)
    {
        $updateCategorySQL = "UPDATE `vendors` SET vendor_name = '$vendorName' WHERE entry_id = '$vendorID'";

        return $this->updateSQLStatement($updateCategorySQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteCategory(String $vendorID)
    {
        $deleteCategorySQL = "DELETE FROM `vendors` WHERE entry_id = '$vendorID'";

        return $this->deleteSQLStatement($deleteCategorySQL, $this->DBConnection);
    }

}
