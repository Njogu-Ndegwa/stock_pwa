<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\Location;

if (!empty($_POST['location_name'])) {
  $Location = new Location();

  $locationName = $Location->sanitiseInput($_POST['location_name']);

  $addLocationResponse = $Location->addLocation($locationName);

  if ($addLocationResponse['response'] == '200') {
    $_SESSION['success'] = "New location has been added to the system successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to add new location";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to add a location are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
