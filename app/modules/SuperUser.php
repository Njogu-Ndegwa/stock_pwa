<?php
declare(strict_types=1);

namespace app;

/**
 * The SuperUser module, 
 * a means through which Super User data is manipulated
 */
class SuperUser extends Database
{
    private $DBConnection;

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
     * Check for the existence of any super user
     */
    public function checkSuperUser():array
    {
        $checkSuperUserSQL = "SELECT * FROM `super_users`";

        return $this->selectSQLStatement($checkSuperUserSQL, $this->DBConnection);
    }

    /**
     * Sanitize the input to prevent against SQL injection
     */
    public function sanitiseInput($inputVariable)
    {
        return $this->sanitiseData($inputVariable, $this->DBConnection);
    }

    /**
     * Create a super user for the system
     */
    public function createSuperUser(String $username, String $email, String $password, String $token, int $code, int $tokenValidity): array
    {
        $createSuperUserSQL = "INSERT INTO `super_users` (username, email, password, token, code, token_validity) VALUES ('$username', '$email', '$password', '$token', $code, $tokenValidity)";

        return $this->insertSQLStatement($createSuperUserSQL, $this->DBConnection);
    }

    /**
     * Check for token validity
     */
    public function checkTokenValidity(String $token): array
    {
        $checkTokenValiditySQL = "SELECT * FROM `super_users` WHERE token = '$token' AND token_validity = 1";

        return $this->selectSQLStatement($checkTokenValiditySQL, $this->DBConnection);
    }

    /**
     * Confirm tokens in logging in
     */
    public function confirmTokens(String $urlToken, String $code): array
    {
        $confirmUserTokenSQL = "SELECT * FROM `super_users` WHERE token = '$urlToken' AND code = '$code' AND token_validity = 1";

        return $this->selectSQLStatement($confirmUserTokenSQL, $this->DBConnection);
    }

    /**
     * Makes the tokens invalid on log out
     */
    public function invalidateTokens(int $userID, String $token): array
    {
        $invalidateTokemSQL = "UPDATE `super_users` SET token_validity = 0 WHERE token = '$token' AND entry_id = '$userID'";

        return $this->updateSQLStatement($invalidateTokemSQL, $this->DBConnection);
    }

    /**
     * Check if credentials of a user exists
     */
    public function loginUser(String $username):array
    {
        $loginUserSQL = "SELECT * FROM `super_users` WHERE email = '$username' OR username = '$username' LIMIT 1";

        return $this->selectSQLStatement($loginUserSQL, $this->DBConnection);
    }

    /**
     * Update tokens for logging in
     */
    public function updateTokens(String $urlToken, String $code, int $userID)
    {
        $updateUserTokenSQL = "UPDATE `super_users` SET token = '$urlToken', code = '$code', token_validity = 1 WHERE entry_id = $userID";

        return $this->updateSQLStatement($updateUserTokenSQL, $this->DBConnection);
    }

    /**
     * Update tokens for reseting the password
     */
    public function setPasswordResetToken(String $urlToken, int $userID)
    {
        $updateResteTokenSQL = "UPDATE `super_users` SET reset_token = '$urlToken', reset_validity = 1 WHERE entry_id = $userID";

        return $this->updateSQLStatement($updateResteTokenSQL, $this->DBConnection);
    }

    /**
     * Check for password reset token validity
     */
    public function checkResetToken(String $token): array
    {
        $checkTokenValiditySQL = "SELECT * FROM `super_users` WHERE reset_token = '$token' AND reset_validity = 1";

        return $this->selectSQLStatement($checkTokenValiditySQL, $this->DBConnection);
    }

    /**
     * Change password for user
     */
    public function changePassword(String $urlToken, String $newPassword)
    {
        $changePasswordSQL = "UPDATE `super_users` SET password = '$newPassword', reset_validity = 0 WHERE reset_token = '$urlToken'";

        return $this->updateSQLStatement($changePasswordSQL, $this->DBConnection);
    }

}
