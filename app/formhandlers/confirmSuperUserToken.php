<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\SuperUser;

if (!empty($_POST['url_token']) && !empty($_POST['code'])) {

  $SuperUser = new SuperUser();

  $urlToken = $SuperUser->sanitiseInput($_POST['url_token']);

  $code = $SuperUser->sanitiseInput($_POST['code']);

  $tokenConfirmResponse = $SuperUser->confirmTokens($urlToken, $code);

  switch ($tokenConfirmResponse['response']) {
      case '200':
          $_SESSION['auth_token'] = $tokenConfirmResponse['data'][0]['token'];
          $_SESSION['auth_uid'] = $tokenConfirmResponse['data'][0]['entry_id'];
          $_SESSION['auth_uname'] = $tokenConfirmResponse['data'][0]['username'];
          header("Location:". $_ENV['APP_URL'] . "/superuser/dashboard");
          break;

      case '204':
          $_SESSION['error'] = "Token invalid";
          header("Location:". $_SERVER['HTTP_REFERER']);
          exit();
          break;

      default:
          $_SESSION['error'] = "There has been an internal error, this has been recorded and will be resolved.";
          header("Location:". $_SERVER['HTTP_REFERER']);
          exit();
          break;
  }

}else {
  $_SESSION['error'] = "Required input to perform token confirmation are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
