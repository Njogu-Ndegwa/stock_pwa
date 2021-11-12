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
        $getMaterialsSQL = "SELECT * FROM `items`";

        return $this->selectSQLStatement($getMaterialsSQL, $this->DBConnection);
    }

    /**
     * Get an item based on ID
     */
    public function getItemById($itemID)
    {
        $getItemSQL = "SELECT * FROM `items` WHERE item_id = '$itemID'";

        return $this->selectSQLStatement($getItemSQL, $this->DBConnection);
    }

    /**
     * Add an item to the system
     */
    public function addItem(String $itemType, String $itemName, $itemCode, String $itemDescription, String $itemQuantity, String $maxThreshold, String $minThreshold, String $standardCost, String $unitCost, String $unitPrice, String $serialNumber)
    {
        $addItemSQL = "INSERT INTO `items`(inventory_type, item_name, item_code, description, quantity, maximum_threshold, minimum_threshold, standard_cost, unit_cost, unit_price, serial_number) VALUES ('$itemType', '$itemName', '$itemCode', '$itemDescription', '$itemQuantity', '$maxThreshold', '$minThreshold', '$standardCost', '$unitCost', '$unitPrice', '$serialNumber')";

        return $this->insertSQLStatement($addItemSQL, $this->DBConnection);
    }
    /**
     * Add material to the system
     */
    public function addMaterial(String $itemName, String $itemCode, String $itemQuantity, String $itemImage, String $itemMinThreshold, String $itemMaxThreshold, String $itemType, String $serialNumber, String $itemPricing)
    {
        $addMaterialSQL = "INSERT INTO `materials`(item_name, material_type, material_code, serial_number, image_url, min_threshold, max_threshold, pricing, quantity) VALUES ('$itemName', '$itemType', '$itemCode', '$serialNumber' , '$itemImage', '$itemMinThreshold', '$itemMaxThreshold', '$itemPricing', '$itemQuantity')";

        return $this->insertSQLStatement($addMaterialSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editItem(String $itemID, String $itemType, String $itemName, String $itemCode, String $itemDescription, String $itemQuantity, String $maxThreshold, String $minThreshold,  String $standardCost, String $serialNumber)
    {
        $updateMaterialSQL = "UPDATE `items` SET inventory_type = '$itemType', item_name = '$itemName', item_code = '$itemCode', description = '$itemDescription', quantity = '$itemQuantity', maximum_threshold = '$maxThreshold', minimum_threshold = '$minThreshold', standard_cost = '$standardCost', serial_number = '$serialNumber' WHERE item_id = '$itemID'";

        return $this->updateSQLStatement($updateMaterialSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteMaterial(String $itemID)
    {
        $deleteItemSQL = "DELETE FROM `items` WHERE item_id = '$itemID'";

        return $this->deleteSQLStatement($deleteItemSQL, $this->DBConnection);
    }

    /**
     * Stock in an item
     */
    public function stockIn(String $itemName, String $locationID, String $warehouseID, String $vendorID, String $invoice, String $lpo, String $quantity, String $deliveryNoteNo, String $vehiclePlate, String $startMileage, String $stopMileage, String $powder, String $color , String $material,  String $pricePerItem, String $costPerItem, String $imageURL)
    {
        $updateQuantitySQL = "UPDATE `items` SET quantity = quantity + '$quantity' WHERE item_id = '$itemName'";

        $this->updateSQLStatement($updateQuantitySQL, $this->DBConnection);

        $stockInSQL = "INSERT INTO `stock_in`(item_name, location_id, warehouse_id, vendor_id, invoice, lpo, quantity, delivery_note_number, vehicle, start_mileage, stop_mileage, powder, color, material, price_per_item, cost_per_item, image_url) VALUES('$itemName', '$locationID', '$warehouseID', '$vendorID' , '$invoice', '$lpo', '$quantity' , '$deliveryNoteNo', '$vehiclePlate', '$startMileage', '$stopMileage', '$powder', '$color', '$material', '$pricePerItem', '$costPerItem', '$imageURL')";

        return $this->insertSQLStatement($stockInSQL, $this->DBConnection);
    }

    /**
     * Remove an item
     */
    public function itemAcquisition(String $vendorID, String $customerID, String $item, String $quantity, String $description, String $date)
    {
        $updateQuantitySQL = "UPDATE `items` SET quantity = quantity - '$quantity' WHERE item_id = '$item'";

        $this->updateSQLStatement($updateQuantitySQL, $this->DBConnection);

        $stockInSQL = "INSERT INTO `inventory_acquisition`(vendor_id, item_id, customer_name, quantity, description, stock_subtracted, date) VALUES('$vendorID', '$item', '$customerID', '$quantity', '$description', '$quantity', '$date')";

        return $this->insertSQLStatement($stockInSQL, $this->DBConnection);
    }

    /**
     * Add a coating job
     */
    public function addCoatingJob( String $customerID, String $coatingJobNumber, String $lpoNo, String $deliveryNo, String $date, String $material, String $weight, String $profileType, String $powderEstimate, String $powderUsed, String $ral, String $color, String $code, String $owner, String $itemsSectionData, String $preparedBy, String $approvedBy, String $supervisor, String $qualityBy, String $inDate, String $outDate, String $readyDate)
    {
        $adCoatingJobSQL = "INSERT INTO `powdercoating_jobs`(coating_job_number, lpo_number, delivery_no, date, material, powder_estimate, powder_used, date_in, color, code, ready_date, belongs_to, out_date, prepared_by, approved_by, supervisor, quality_by, profile_type, goods_in_weight, items) VALUES('$coatingJobNumber', '$lpoNo', '$deliveryNo', '$date', '$material', '$powderEstimate', '$powderUsed', '$inDate', '$color', '$code', '$readyDate', '$owner', '$outDate', '$preparedBy', '$approvedBy', '$supervisor', '$qualityBy', '$profileType', '$weight', '$itemsSectionData')";

        return $this->insertSQLStatement($adCoatingJobSQL, $this->DBConnection);
    }



}
