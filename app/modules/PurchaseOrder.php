<?php

namespace app;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class PurchaseOrder extends Database
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
    public function getPurchaseOrders()
    {
        $getPurchaseOrderSQL = "SELECT * FROM `purchase_order`";

        return $this->selectSQLStatement($getPurchaseOrderSQL, $this->DBConnection);
    }

    /**
     * Add Purchase Order
     */
    public function addPurchaseOrder(String $vendorName, String $item, String $recordDate, String $dueDate, String $quotationReference, String $quotationDate, String $itemDescription, int $qty, int $amount, String $termsConditions, int $unitCost,   $poStatus, String $createdBy)
    {
        $addPurchaseOrderSQL = "INSERT INTO `purchase_order`(vendor_name, item, record_date, due_date, quotation_reference, quotation_date, item_description, qty, amount, terms_and_conditions, unit_cost, po_status, created_by) VALUES ('$vendorName', '$item', '$recordDate', '$dueDate', '$quotationReference', '$quotationDate', '$purchaseDescription', '$qty', '$amount', '$termsConditions', '$unitCost', '  $poStatus', '$createdBy')";

        return $this->insertSQLStatement($addPurchaseOrderSQL, $this->DBConnection);
    }

    /**
     * Edit Purchase Order
     */
    public function editPurchaseOrder(String $purchaseOrderID, String $vendorName, String $item, String $poStatus, String $updatedBy, String $updateTime)
    {
        $updatePurchaseOrderSQL = "UPDATE `purchase_order` SET vendor_name = '$vendorName', item = '$item', po_status = '$poStatus', updated_by = '$updatedBy', updated_at = '$updateTime' WHERE purchase_order_id = '$purchaseOrderID'";

        return $this->updateSQLStatement($updatePurchaseOrderSQL, $this->DBConnection);
    }

    /**
     * Delete Purchase Order
     */
    public function deletePurchaseOrder(String $purchaseOrderID)
    {
        $deletePurchaseOrderSQL = "DELETE FROM `purchase_order` WHERE purchase_order_id = '$purchaseOrderID'";

        return $this->deleteSQLStatement($deletePurchaseOrderSQL, $this->DBConnection);
    }

}
