<?php

namespace app;

/**
 * This is the Category module
 * this handles a category's actions and
 * manipulating its data
 */
class Material extends Database
{
    private $DBConnection;

    public function __construct()
    {
        $connectionAttempt = $this->dbConnect();
        if ($connectionAttempt['response'] == '200') {
            $this->DBConnection = $connectionAttempt['data'];
        } else {
            Logger::logToFile('Error', $connectionAttempt['message']);
            throw new \Exception("Error experienced connecting the Material module to the database", 1);
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
     * Get all the materials present
     */
    public function getMaterials()
    {
        $getMaterialsSQL = "SELECT * FROM `materials`";

        return $this->selectSQLStatement($getMaterialsSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addMaterial(String $itemName, String $itemCode, String $itemQuantity, String $itemImage, String $itemMinThreshold, String $itemMaxThreshold, String $itemType, String $serialNumber, String $itemPricing)
    {
        $addMaterialSQL = "INSERT INTO `materials`(item_name, material_type, material_code, serial_number, image_url, min_threshold, max_threshold, pricing, quantity) VALUES ('$itemName', '$itemType', '$itemCode', '$serialNumber' , '$itemImage', '$itemMinThreshold', '$itemMaxThreshold', '$itemPricing', '$itemQuantity')";

        return $this->insertSQLStatement($addMaterialSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editMaterial(String $materialID, String $itemName, String $itemCode, String $itemQuantity, String $itemImage, String $itemMinThreshold, String $itemMaxThreshold, String $itemType, String $serialNumber, String $itemPricing)
    {
        $updateMaterialSQL = "UPDATE `materials` SET item_name = '$itemName', material_type = '$itemType', material_code = '$itemCode',serial_number = '$serialNumber', image_url = '$itemImage', min_threshold = '$itemMinThreshold', max_threshold = '$itemMaxThreshold', pricing = '$itemPricing', quantity = '$itemQuantity'  WHERE material_id = '$materialID'";

        return $this->updateSQLStatement($updateMaterialSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteMaterial(String $materialID)
    {
        $deleteMaterialSQL = "DELETE FROM `materials` WHERE material_id = '$materialID'";

        return $this->deleteSQLStatement($deleteMaterialSQL, $this->DBConnection);
    }

    /**
     * Stock in an item
     */
    public function stockIn(String $itemName, String $itemCode, String $locationID, String $warehouseID, String $vendorID, String $invoice, String $lpo, String $quantity, String $deliveryNoteNo, String $vehiclePlate, String $startMileage, String $stopMileage, String $powder, String $color , String $material,  String $pricePerItem, String $costPerItem, String $imageURL, String $minimumThreshold, String $maximumThreshold)
    {
        $stockInSQL = "INSERT INTO `stock_in`(item_name, item_code, location_id, warehouse_id, vendor_id, invoice, lpo, quantity, delivery_note_number, vehicle, start_mileage, stop_mileage, powder, color, material, price_per_item, cost_per_item, image_url ,minimum_threshold, maximum_threshold) VALUES('$itemName', '$itemCode', '$locationID', '$warehouseID', '$vendorID' , '$invoice', '$lpo', '$quantity' , '$deliveryNoteNo', '$vehiclePlate', '$startMileage', '$stopMileage', '$powder', '$color', '$material', '$pricePerItem', '$costPerItem', '$imageURL', '$minimumThreshold', '$maximumThreshold')";

        return $this->insertSQLStatement($stockInSQL, $this->DBConnection);
    }

}
