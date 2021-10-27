<?php

namespace app;

/**
 * This is the User module handling access to the system
 */
class User extends Database
{
    private $DBConnection;
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $connectionAttempt = $this->dbConnect();
        if ($connectionAttempt['response'] == '200') {
            $this->DBConnection = $connectionAttempt['data'];
        } else {
            Logger::logToFile('Error', $connectionAttempt['message']);
            throw new \Exception("Error experienced connecting the User module to the database", 1);
            exit();
        }

    }

    public function sanitiseInput($inputVariable)
    {
        return $this->sanitiseData($inputVariable, $this->DBConnection);
    }

    public function loginUser(String $username, String $password)
    {
        $loginUserSQL = "SELECT * FROM `users` WHERE email = '$username' OR username = '$username' AND password = '$password' LIMIT 1";

        return $this->selectSQLStatement($loginUserSQL, $this->DBConnection);
    }
}
