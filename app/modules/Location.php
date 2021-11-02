<?php

namespace app;

/**
 * This is the Company module
 * this handles a company's actions and
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
    public function addLocation(String $locationName)
    {
        $addLocationSQL = "INSERT INTO `locations`(location_name) VALUES ('$locationName')";

        return $this->insertSQLStatement($addLocationSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function editLocation(String $locationID, String $locationName)
    {
        $updateLocationSQL = "UPDATE `locations` SET location_name = '$locationName' WHERE entry_id = '$locationID'";

        return $this->updateSQLStatement($updateLocationSQL, $this->DBConnection);
    }

    /**
     * Get all the companies present
     */
    public function deleteLocation(String $locationID)
    {
        $deleteLocationSQL = "DELETE FROM `locations` WHERE entry_id = '$locationID'";

        return $this->deleteSQLStatement($deleteLocationSQL, $this->DBConnection);
    }

}
