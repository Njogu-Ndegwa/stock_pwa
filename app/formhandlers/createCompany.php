<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

require_once '../mailtemplates/companyEmailTemplates.php';

use app\SuperUser;

use app\Mail;

if (!empty($_POST['company_email']) && !empty($_POST['company_name']) && !empty($_POST['activation_key']) && !empty($_POST['duration_number']) && !empty($_POST['duration_period'])) {

  $SuperUser = new SuperUser();

  $companyEmail = $SuperUser->sanitiseInput($_POST['company_email']);

  $companyName = $SuperUser->sanitiseInput($_POST['company_name']);

  $activationKey = $SuperUser->sanitiseInput($_POST['activation_key']);

  $durationNumber = $SuperUser->sanitiseInput($_POST['duration_number']);

  $durationPeriod = $SuperUser->sanitiseInput($_POST['duration_period']);

  $durationString = $durationNumber. "-" .$durationPeriod;

  $timeDurationNumber = $durationNumber;

  $timeAdd = 0;

  if ($_POST['duration_period'] == 'days') {
    $timeAdd = $timeDurationNumber * 86400;
  }elseif ($_POST['duration_period'] == 'months') {
    $timeAdd = $timeDurationNumber * 2592000;
  }elseif ($_POST['duration_period'] == 'years') {
    $timeAdd = $timeDurationNumber * 31104000;
  }

  $trial = 0;

  if (isset($_POST['trial'])) {
    $trial = 1;
  }

  $subscriptionUUID = sha1(time());

  $subscriptionExpiry = time() + $timeAdd;

  $loginToken = md5(time());

  $messageBody = accountCreation($_POST['company_name'], $activationKey, $_ENV['APP_URL'], $loginToken);

  $createCompanyResponse = $SuperUser->createCompany($companyEmail, $companyName, $activationKey, $subscriptionUUID, $subscriptionExpiry, $trial, $loginToken);

  if ($createCompanyResponse['response'] == '200') {

    // TODO: Send email functionality
    $senderOptions = array(
      'email_host' => $_ENV['EMAIL_HOST'],
      'email_username' => $_ENV['EMAIL_USERNAME'],
      'email_password' => $_ENV['EMAIL_PASSWORD'],
      'smtp_secure_options' => $_ENV['SMTP_SECURE_OPTION'],
      'email_port' => $_ENV['EMAIL_PORT']
    );

    Mail::sendEmail($senderOptions, $_POST['company_email'], 'Company Account Activation', $_POST['company_name'], $messageBody);

    $_SESSION['success'] = "Company has been created email sent to <u>". $_POST['company_email'] ."</u> containing instructions to proceed";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to create company. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to create a company in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
