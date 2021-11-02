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
        $getSubcategoriesSQL = "SELECT subcategories.entry_id as entry_id, category_name, subcategory_name FROM `subcategories` INNER JOIN categories ON categories.entry_id = subcategories.category_id";

        return $this->selectSQLStatement($getSubcategoriesSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addSubcategory(String $subcategoryName, String $categoryID)
    {
        $addSubcategorySQL = "INSERT INTO `subcategories`(subcategory_name, category_id) VALUES ('$subcategoryName', '$categoryID')";

        return $this->insertSQLStatement($addSubcategorySQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editSubcategory(String $subcategoryID, String $categoryID, String $subcategoryName)
    {
        $updateSubcategorySQL = "UPDATE `subcategories` SET category_id = '$categoryID', subcategory_name='$subcategoryName' WHERE entry_id = '$subcategoryID'";

        return $this->updateSQLStatement($updateSubcategorySQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteSubcategory(String $subcategoryID)
    {
        $deletesubCategorySQL = "DELETE FROM `subcategories` WHERE entry_id = '$subcategoryID'";

        return $this->deleteSQLStatement($deletesubCategorySQL, $this->DBConnection);
    }

}
