<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

require_once '../mailtemplates/loginToken.php';

use app\User;

use app\Mail;

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $User = new User();

    $username = $User->sanitiseInput($_POST['username']);

    $loginUserResponse = $User->loginUser($username);

    switch ($loginUserResponse['response']) {
        case '200':
            if (!password_verify($_POST['password'], $loginUserResponse['data'][0]['password'])) {
              $_SESSION['error'] = "No matching credentials found!";
              header("Location:". $_SERVER['HTTP_REFERER']);
              exit();
            }

            $token = md5(time());

            $code = intval($User->generateToken(6, 1, 'numbers')[0]);

            $User->updateTokens($token, $code, $loginUserResponse['data'][0]['entry_id']);

            $messageBody = loginTokenTemplate($loginUserResponse['data'][0]['username'], $code, $_ENV['APP_URL'], $token);

            $senderOptions = array(
              'email_host' => $_ENV['EMAIL_HOST'],
              'email_username' => $_ENV['EMAIL_USERNAME'],
              'email_password' => $_ENV['EMAIL_PASSWORD'],
              'smtp_secure_options' => $_ENV['SMTP_SECURE_OPTION'],
              'email_port' => $_ENV['EMAIL_PORT']
            );

            Mail::sendEmail($senderOptions, $loginUserResponse['data'][0]['email'], 'Login Token', $loginUserResponse['data'][0]['username'], $messageBody);

            $_SESSION['success'] = "Email sent to <u>". $_POST['email'] ."</u> containing verification code for logging in";

            header("Location:". $_ENV['APP_URL'] ."/logintoken/". $token );
            exit();
            break;

        case '204':
            $_SESSION['error'] = "No matching credentials found!";
            header("Location:". $_SERVER['HTTP_REFERER']);
            exit();
            break;

        default:
            $_SESSION['error'] = "There has been an internal error, this has been recorded and will be resolved.";
            header("Location:". $_SERVER['HTTP_REFERER']);
            exit();
            break;
    }

}else{
    $_SESSION['error'] = "Required input to perform login are missing";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
}
