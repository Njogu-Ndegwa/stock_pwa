<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Location;

if (!empty($_POST['location_name']) && !empty($_POST['location_description']) && !empty($_POST['location_status'])) {
  $Location = new Location();

  $locationName = $Location->sanitiseInput($_POST['location_name']);

  $locationDescription = $Location->sanitiseInput($_POST['location_description']);

  $locationStatus = $Location->sanitiseInput($_POST['location_status']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $addLocationResponse = $Location->addLocation($locationName, $locationDescription, $locationStatus,  $userID);

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
