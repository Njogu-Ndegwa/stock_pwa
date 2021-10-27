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

    /**
     * Sanitize the input to prevent against SQL injection
     */
    public function sanitiseInput($inputVariable)
    {
        return $this->sanitiseData($inputVariable, $this->DBConnection);
    }

    /**
     * Check if credentials of a user exists
     */
    public function loginUser(String $username, String $password):array
    {
        $loginUserSQL = "SELECT * FROM `users` WHERE email = '$username' OR username = '$username' AND password = '$password' LIMIT 1";

        return $this->selectSQLStatement($loginUserSQL, $this->DBConnection);
    }

    /**
     * Checks if there is a user with super admin status
     */
    public function checkSuperAdministrator():array
    {
        $checkSuperAdministratorSQL = "SELECT * FROM `users` WHERE super_admin = 1";

        return $this->selectSQLStatement($checkSuperAdministratorSQL, $this->DBConnection);
    }

    /**
     * Add to the list of users
     */
    public function addUser(String $username, String $email, String $password, String $token, int $code, bool $superAdmin)
    {
        $insertUserSQL = "INSERT INTO `users` (username, email, password, token, code, super_admin) VALUES('$username', '$email', '$password', '$token', $code, $superAdmin)";

        return $this->insertSQLStatement($insertUserSQL, $this->DBConnection);
    }

    /**
     * Add to the list of users
     */
    public function confirmTokens(String $urlToken, String $code)
    {
        $confirmUserTokenSQL = "SELECT * FROM `users` WHERE token = '$urlToken' AND code = '$code'";

        return $this->selectSQLStatement($confirmUserTokenSQL, $this->DBConnection);
    }

    /**
     * Update the verification status depending on scenario
     */
    public function changeVerificationStatus(int $verificationStatus)
    {
        $changeVerificationStatusSQL = "UPDATE `users` SET verified = '$verificationStatus'";

        return $this->updateSQLStatement($changeVerificationStatusSQL, $this->DBConnection);
    }


}
