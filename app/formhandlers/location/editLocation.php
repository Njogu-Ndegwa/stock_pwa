<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Location;

if (!empty($_POST['location_name']) && !empty($_POST['location_description']) && !empty($_POST['location_status']) && !empty($_POST['location_id'])) {
  $Location = new Location();

  $locationName = $Location->sanitiseInput($_POST['location_name']);

  $locationDescription = $Location->sanitiseInput($_POST['location_description']);

  $locationStatus = $Location->sanitiseInput($_POST['location_status']);

  $userID  = (!empty($_SESSION['auth_uid'])) ? $_SESSION['auth_uid'] : "0" ;

  $locationID = $Location->sanitiseInput($_POST['location_id']);

  $updateTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);

  $editLocationResponse = $Location->editLocation($locationName, $locationDescription, $locationStatus, $locationID, $userID, $updateTime);

  if ($editLocationResponse['response'] == '200') {
    $_SESSION['success'] = "New location has been edited successfuly";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['success'] = "Failed to edit the location";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }
}else {
  $_SESSION['error'] = "Required input to edit location are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
