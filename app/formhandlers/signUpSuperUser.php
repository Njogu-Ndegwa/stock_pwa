<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

require_once '../mailtemplates/superAdmin.php';

use app\Mail;
use app\User;

if (!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['conf_password'])) {

    if ($_POST['conf_password'] != $_POST['password']) {
      $_SESSION['error'] = "Password and confirm password values do not match";
      header("Location:". $_SERVER['HTTP_REFERER']);
      exit();
    }
    $User = new User();

    $email = $User->sanitiseInput($_POST['email']);

    $username = $User->sanitiseInput($_POST['username']);

    $passwordOptions = [
      'cost' => 12,
    ];

    $passwordToDB = password_hash($_POST['password'], PASSWORD_BCRYPT, $passwordOptions);

    $password = $User->sanitiseInput($passwordToDB);

    $token = md5(time());

    $code = intval($User->generateToken(6, 1, 'numbers')[0]);

    $messageBody = superAdminTemplate($username, $code, $_ENV['APP_URL'], $token);

    $addUserResponse = $User->addUser($username, $email, $password, $token, $code, 1);

    if ($addUserResponse['response'] == '200') {
      $senderOptions = array(
        'email_host' => $_ENV['EMAIL_HOST'],
        'email_username' => $_ENV['EMAIL_USERNAME'],
        'email_password' => $_ENV['EMAIL_PASSWORD'],
        'smtp_secure_options' => $_ENV['SMTP_SECURE_OPTION'],
        'email_port' => $_ENV['EMAIL_PORT']
      );

      Mail::sendEmail($senderOptions, $_POST['email'], 'Super Administrator Creation', $_POST['username'], $messageBody);

      $_SESSION['success'] = "Super administrator has been created, email sent to <u>". $_POST['email'] ."</u> containing verification code";

      header("Location:". $_ENV['APP_URL'] ."/logintoken/". $token );

      exit();
    }else {

      $_SESSION['error'] = "Failed to create super administrator due to an internal server error. This has been recorded and will be resolved";

      header("Location:". $_SERVER['HTTP_REFERER'] );
      exit();

    }


}else{
    $_SESSION['error'] = "Required input to perform super administrator sign up are missing";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
}
