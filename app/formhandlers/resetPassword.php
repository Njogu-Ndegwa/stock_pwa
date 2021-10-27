<?php
require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\User;

if (!empty($_POST['new_password'])) {

  $User = new User();
  
  $passwordOptions = [
    'cost' => 12,
  ];

  $passwordToDB = password_hash($_POST['new_password'], PASSWORD_BCRYPT, $passwordOptions);

  $password = $User->sanitiseInput($passwordToDB);

  $urlToken = $User->sanitiseInput($_POST['url_token']);

  $updatePasswordResponse = $User->changePassword($urlToken, $password);

  if ($updatePasswordResponse['response'] == '200') {
    
    $_SESSION['success'] = "Your password has been reset successfully. You can login with the new credentials.";

    header("Location:". $_ENV['APP_URL']);
    exit();
  }else{
    $_SESSION['error'] = "There has been an error changing your password. It has been recorded and will be resolved.";
    header("Location:". $_ENV['APP_URL']);
    exit();
  }


}
