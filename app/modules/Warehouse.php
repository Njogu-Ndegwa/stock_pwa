<?php

namespace app;

/**
 * This is the Warehouse module
 * this handles a warehouse's actions and
 * manipulating its data
 */
class Warehouse extends Database
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
    public function getWarehouses()
    {
      $getWarehousesSQL = "SELECT * FROM `warehouses` INNER JOIN locations ON locations.entry_id = warehouses.location_id WHERE is_deleted = 0";

        return $this->selectSQLStatement($getWarehousesSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addWarehouse(String $warehouseName, String $warehouseDescription, String $warehouseStatus, String $locationID, String $createdBy)
    {
        $addWarehouseSQL = "INSERT INTO `warehouses`(warehouse_name, warehouse_status, warehouse_description, location_id, created_by) VALUES ('$warehouseName', '$warehouseStatus', '$warehouseDescription','$locationID', '$createdBy')";

        return $this->insertSQLStatement($addWarehouseSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editWarehouse(String $warehouseID, String $locationID, String $warehouseName, String $warehouseDescription, String $warehouseStatus, String $updatedBy, String $updateTime)
    {
        $updateWarehouseSQL = "UPDATE `warehouses` SET location_id = '$locationID', warehouse_name = '$warehouseName', warehouse_description = '$warehouseDescription', warehouse_status = '$warehouseStatus', updated_by = '$updatedBy', updated_at = '$updateTime' WHERE warehouse_id = '$warehouseID'";

        return $this->updateSQLStatement($updateWarehouseSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteWarehouse(String $warehouseID)
    {
        $deleteWarehouseSQL = "DELETE FROM `warehouses` WHERE warehouse_id = '$warehouseID'";

        return $this->deleteSQLStatement($deleteWarehouseSQL, $this->DBConnection);
    }

}
