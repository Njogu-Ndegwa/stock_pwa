<?php

namespace app;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class Category extends Database
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
    public function getCategories()
    {
        $getCategoriesSQL = "SELECT * FROM `categories`";

        return $this->selectSQLStatement($getCategoriesSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addCategory(String $categoryName)
    {
        $addCategorySQL = "INSERT INTO `categories`(category_name) VALUES ('$categoryName')";

        return $this->insertSQLStatement($addCategorySQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editCategory(String $categoryID, String $categoryName)
    {
        $updateCategorySQL = "UPDATE `categories` SET category_name = '$categoryName' WHERE entry_id = '$categoryID'";

        return $this->updateSQLStatement($updateCategorySQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteCategory(String $categoryID)
    {
        $deleteCategorySQL = "DELETE FROM `categories` WHERE entry_id = '$categoryID'";

        return $this->deleteSQLStatement($deleteCategorySQL, $this->DBConnection);
    }

}
