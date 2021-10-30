<?php
require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\SuperUser;

if (!empty($_POST['new_password'])) {

  $SuperUser = new SuperUser();

  $passwordOptions = [
    'cost' => 12,
  ];

  $passwordToDB = password_hash($_POST['new_password'], PASSWORD_BCRYPT, $passwordOptions);

  $password = $SuperUser->sanitiseInput($passwordToDB);

  $urlToken = $SuperUser->sanitiseInput($_POST['url_token']);

  $updatePasswordResponse = $SuperUser->changePassword($urlToken, $password);

  if ($updatePasswordResponse['response'] == '200') {

    $_SESSION['success'] = "Your password has been reset successfully. You can login with the new credentials.";
    header("Location:". $_ENV['APP_URL'] .'/superuser');
    exit();
  }else{
    $_SESSION['error'] = "There has been an error changing your password. It has been recorded and will be resolved.";
    header("Location:". $_ENV['APP_URL'] . '/superuser');
    exit();
  }

}
