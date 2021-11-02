<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\Location;

if (!empty($_POST['location_name']) && !empty($_POST['location_id'])) {
  $Location = new Location();

  $locationName = $Location->sanitiseInput($_POST['location_name']);

  $locationID = $Location->sanitiseInput($_POST['location_id']);

  $editLocationResponse = $Location->editLocation($locationID, $locationName);

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
