<?php
require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

require_once '../mailtemplates/forgotPasswordTemplates.php';

use app\SuperUser;

use app\Mail;

if (!empty($_POST['username'])) {
  $SuperUser = new SuperUser;

  $username = $SuperUser->sanitiseInput($_POST['username']);

  $checkUserResponse = $SuperUser->loginUser($username);

  switch ($checkUserResponse['response']) {
    case '200':

    $token = md5(time());

    $code = intval($SuperUser->generateToken(6, 1, 'numbers')[0]);

    $SuperUser->setPasswordResetToken($token, $checkUserResponse['data'][0]['entry_id']);

    $messageBody = forgotPasswordSuperUser($checkUserResponse['data'][0]['username'], $code, $_ENV['APP_URL'], $token);

    $senderOptions = array(
      'email_host' => $_ENV['EMAIL_HOST'],
      'email_username' => $_ENV['EMAIL_USERNAME'],
      'email_password' => $_ENV['EMAIL_PASSWORD'],
      'smtp_secure_options' => $_ENV['SMTP_SECURE_OPTION'],
      'email_port' => $_ENV['EMAIL_PORT']
    );

    Mail::sendEmail($senderOptions, $checkUserResponse['data'][0]['email'], 'Password Reset Request', $checkUserResponse['data'][0]['username'], $messageBody);

    $_SESSION['success'] = "Email sent to <u>". $checkUserResponse['data'][0]['email'] ."</u> containing link for password resetting";

    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
    break;

      break;
      case '204':
        $_SESSION['error'] = "Unable to initiate the forgot password request. No credentials matched.";
        header("Location:". $_SERVER['HTTP_REFERER']);
        exit();
        break;
    default:
      $_SESSION['error'] = "Unable to initiate the forgot password request. The server has experienced a problem which has been recorded and will be resolved";
      header("Location:". $_SERVER['HTTP_REFERER']);
      exit();
      break;
  }
}else {
  $_SESSION['error'] = "Required input to perform forgot password action is not provided";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
