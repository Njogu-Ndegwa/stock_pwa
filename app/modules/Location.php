<?php

namespace app;

/**
 * This is the Location module
 * this handles a locations's actions and
 * manipulating its data
 */
class Location extends Database
{
    private $DBConnection;

    public function __construct()
    {
        $connectionAttempt = $this->dbConnect();
        if ($connectionAttempt['response'] == '200') {
            $this->DBConnection = $connectionAttempt['data'];
        } else {
            Logger::logToFile('Error', $connectionAttempt['message']);
            throw new \Exception("Error experienced connecting the Location module to the database", 1);
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
    public function getLocations()
    {
        $getLocationsSQL = "SELECT * FROM `locations`";

        return $this->selectSQLStatement($getLocationsSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function addLocation(String $locationName, String $locationDescription, String $locationStatus, String $createdBy)
    {
        $addLocationSQL = "INSERT INTO `locations`(location_name, location_description, location_status, created_by) VALUES ('$locationName', '$locationDescription', '$locationName', '$createdBy')";

        return $this->insertSQLStatement($addLocationSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editLocation(String $locationName, String $locationDescription, String $locationStatus, String $locationID, String $userID, String $updateTime)
    {
        $updateLocationSQL = "UPDATE `locations` SET location_name = '$locationName', location_description = '$locationDescription', location_status = '$locationStatus', updated_by = '$userID', updated_at = '$updateTime' WHERE location_id = '$locationID'";

        return $this->updateSQLStatement($updateLocationSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteLocation(String $locationID)
    {
        $deleteLocationSQL = "DELETE FROM `locations` WHERE location_id = '$locationID'";

        return $this->deleteSQLStatement($deleteLocationSQL, $this->DBConnection);
    }

}
