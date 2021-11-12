<?php

namespace app;

/**
 * This is the Purchasesmodule
 * this handles a purchases's actions and
 * manipulating its data
 */
class Purchase extends Database
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
    public function getPurchases()
    {
        $getPurchasesSQL = "SELECT * FROM `purchases`";

        return $this->selectSQLStatement($getPurchasesSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addPurchase(String $vendorName, String $item, String $recordDate, String $dueDate, String $quotationReference, String $quotationDate, String $purchaseDescription, int $qty, int $amount, String $termsConditions, int $unitCost,   $purchaseStatus, String $createdBy)
    {
        $addPurchaseSQL = "INSERT INTO `purchases`(vendor_name, item, record_date, due_date, quotation_reference, quotation_date, purchase_description, qty, amount, terms_and_conditions, unit_cost, purchase_status, created_by) VALUES ('$vendorName', '$item', '$recordDate', '$dueDate', '$quotationReference', '$quotationDate', '$purchaseDescription', '$qty', '$amount', '$termsConditions', '$unitCost', '  $purchaseStatus', '$createdBy')";

        return $this->insertSQLStatement($addPurchaseSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editPurchase(String $purchaseID, String $vendorName, String $item, String $status, String $updatedBy, String $updateTime)
    {
        $updatePurchaseSQL = "UPDATE `purchases` SET vendor_name = '$vendorName', item = '$item', purchase_status = '$status', updated_by = '$updatedBy', updated_at = '$updateTime' WHERE purchase_id = '$purchaseID'";

        return $this->updateSQLStatement($updatePurchaseSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deletePurchase(String $purchaseID)
    {
        $deletePurchaseSQL = "DELETE FROM `purchases` WHERE purchase_id = '$purchaseID'";

        return $this->deleteSQLStatement($deletePurchaseSQL, $this->DBConnection);
    }

}
