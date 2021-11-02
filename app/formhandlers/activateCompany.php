<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\Company;

if (!empty($_POST['url_token']) && !empty($_POST['activation_key'])) {

  $Company = new Company();

  $urlToken = $Company->sanitiseInput($_POST['url_token']);

  $activationKey = $Company->sanitiseInput($_POST['activation_key']);

  $tokenConfirmResponse = $Company->confirmTokens($urlToken, $activationKey);

  switch ($tokenConfirmResponse['response']) {
      case '200':

          $Company->invalidateKey($activationKey);
          $_SESSION['auth_token'] = $tokenConfirmResponse['data'][0]['login_token'];
          $_SESSION['auth_uid'] = $tokenConfirmResponse['data'][0]['entry_id'];
          $_SESSION['auth_uname'] = $tokenConfirmResponse['data'][0]['company_name'];
          header("Location:". $_ENV['APP_URL'] . "/dashboard");
          break;

      case '204':
          $_SESSION['error'] = "No matching key found!";
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
