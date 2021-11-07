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
            throw new \Exception("Error experienced connecting the Vendor module to the database", 1);
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
    public function addVendor(String $vendorName, String $vendorEmail, String $vendorPhone, String $vendorDescription)
    {
        $addVendorSQL = "INSERT INTO `vendors`(vendor_name, vendor_email, vendor_mobile, vendor_description) VALUES ('$vendorName', '$vendorEmail', '$vendorPhone', '$vendorDescription')";

        return $this->insertSQLStatement($addVendorSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editVendor(String $vendorID, String $vendorName, String $vendorEmail, String $vendorPhone, String $vendorDescription, String $updateTime)
    {
        $updateCategorySQL = "UPDATE `vendors` SET vendor_name = '$vendorName', vendor_email = '$vendorEmail', vendor_mobile = '$vendorPhone', vendor_description = '$vendorDescription' WHERE vendor_id = '$vendorID'";

        return $this->updateSQLStatement($updateCategorySQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteCategory(String $vendorID)
    {
        $deleteCategorySQL = "DELETE FROM `vendors` WHERE vendor_id = '$vendorID'";

        return $this->deleteSQLStatement($deleteCategorySQL, $this->DBConnection);
    }

}
