<?php

namespace app;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class Subcategory extends Database
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
    public function getSubcategories()
    {
        $getSubcategoriesSQL = "SELECT * FROM `subcategories` INNER JOIN categories ON categories.category_id = subcategories.category_id";

        return $this->selectSQLStatement($getSubcategoriesSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addSubcategory(String $subcategoryName, String $subcategoryDescription, String $subcategoryStatus, String $categoryID, String $createdBy)
    {
        $addSubcategorySQL = "INSERT INTO `subcategories`(subcategory_name, subcategory_status, subcategory_description, category_id, created_by) VALUES ('$subcategoryName', '$subcategoryStatus', '$subcategoryDescription' ,'$categoryID', '$createdBy')";

        return $this->insertSQLStatement($addSubcategorySQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editSubcategory(String $subcategoryID, String $categoryID, String $subcategoryName, String $subcategoryStatus, String $subcategoryDescription, String $updatedBy, String $updateTime)
    {
        $updateSubcategorySQL = "UPDATE `subcategories` SET category_id = '$categoryID', subcategory_name='$subcategoryName', subcategory_status = '$subcategoryStatus', subcategory_description = '$subcategoryDescription', updated_at = '$updateTime', updated_by = '$updatedBy' WHERE subcategory_id = '$subcategoryID'";

        return $this->updateSQLStatement($updateSubcategorySQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteSubcategory(String $subcategoryID)
    {
        $deletesubCategorySQL = "DELETE FROM `subcategories` WHERE subcategory_id = '$subcategoryID'";

        return $this->deleteSQLStatement($deletesubCategorySQL, $this->DBConnection);
    }

}
