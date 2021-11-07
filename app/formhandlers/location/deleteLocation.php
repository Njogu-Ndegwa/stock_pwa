<?php

require_once '../../vendor/autoload.php';

require_once '../postMiddleware.php';

use app\Location;

if (!empty($_POST['location_id'])) {

  $Location = new Location();

  $locationID = $Location->sanitiseInput($_POST['location_id']);

  $deleteLocationResponse = $Location->deleteLocation($locationID);

  if ($deleteLocationResponse['response'] == '200') {

    $_SESSION['success'] = "Location has been deleted";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }else {
    $_SESSION['error'] = "Failed to delete location. Error has been logged";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
  }

}else {
  $_SESSION['error'] = "Required input to delete a company in system are missing";
  header("Location:". $_SERVER['HTTP_REFERER']);
  exit();
}
