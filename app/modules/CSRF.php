<?php
declare(strict_types=1);

namespace app;

if (!isset($_SESSION)) {
    session_start();
}

/**
 * The CSRF module providing a unique token for each form sent
 */
class CSRF
{
    /**
     * Create a token for the form
     */
    public static function createToken()
    {
        $token = sha1(time().'CSRF');
        $_SESSION['token']= $token;
        return "<input type='hidden' name='token' value='$token'/>";
    }

    /**
     * Create a token for the form, method is used in templates
     */
    public static function generateCSRFToken()
    {
        $token = sha1(time().'CSRF');
        $_SESSION['token']= $token;
        return "<input type='hidden' name='token' value='$token'/>";
    }

    /**
     * Validating the token
     */
    public static function validate_token($token)
    {
        return isset($_SESSION['token']) && $_SESSION['token']==$token;
    }
}
