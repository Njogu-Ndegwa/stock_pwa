<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_SESSION['auth_token']) || empty($_SESSION['auth_uid']) || empty($_SESSION['auth_uname'])) {
  header("HTTP/1.1 403 Forbidden");

  $forbiddenPage = file_get_contents('./403.php');

  // exit($forbiddenPage);
}

require_once 'app/vendor/autoload.php';

use app\CSRF;

use app\Location;

$Location = new Location();

$getLocationsResponse = $Location->getLocations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/dashboard.min.css">
  <link rel="stylesheet" href="assets/css/casual.min.css">
  <link rel="stylesheet" href="assets/css/table.min.css">
  <link rel="stylesheet" href="assets/css/alert.min.css">
  <title>Locations</title>
</head>
<body>
  <?php
    if (isset($_SESSION['error'])) {
  ?>
  <div class="alert error">
    <span><?php echo $_SESSION['error'] ?></span>
    <span onclick="closeAlert(this)">&times;</span>
  </div>
  <?php
    unset($_SESSION['error']);
    }
  ?>

  <?php
    if (isset($_SESSION['success'])) {
  ?>
  <div class="alert success">
    <span><?php echo $_SESSION['success'] ?></span>
    <span onclick="closeAlert(this)">&times;</span>
  </div>
  <?php
    unset($_SESSION['success']);
    }
  ?>

  <div class="main">

    <div class="navigation-bar glassmorphic">
      <div class="logo-section">
        <img src="assets/images/briefcase-solid.svg" alt="briefcase-solid Font Awesome icon">
        Logo
      </div>

      <div class="navigation-items-container">
        <a href="dashboard" class="navigation-item">
          <img src="assets/images/home-solid.svg" alt="home-solid Font Awesome icon">
          Dashboard
        </a>

        <h3>Projects</h3>

        <a href="#" class="navigation-item">
          <img src="assets/images/industry-solid.svg" alt="industry-solid Font Awesome icon">
          Powder Coating
        </a>

        <h3>Office</h3>
        <a href="locations" class="navigation-item active">
          <img src="assets/images/map-marker-alt-solid.svg" alt="map-marker-alt-solid Font Awesome icon">
          Locations
        </a>

        <a href="warehouses" class="navigation-item">
          <img src="assets/images/store-alt-solid.svg" alt="store-alt-solid Font Awesome icon">
          Warehouses
        </a>

        <a href="categories" class="navigation-item">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Categories
        </a>

        <a href="subcategories" class="navigation-item">
          <img src="assets/images/chess-pawn-solid.svg" alt="chess-pawn-solid Font Awesome icon">
          Subcategories
        </a>

        <a href="vendors" class="navigation-item">
          <img src="assets/images/people-carry-solid.svg" alt="people-carry-solid Font Awesome icon">
          Vendor/Supplier
        </a>

        <a href="customers" class="navigation-item">
          <img src="assets/images/user-friends-solid.svg" alt="user-friends-solid Font Awesome icon">
          Customers
        </a>

        <a href="inventory" class="navigation-item">
          <img src="assets/images/money-check-alt-solid.svg" alt="money-check-alt-solid Font Awesome icon">
          Inventory
        </a>

        <a href="settings" class="navigation-item">
          <img src="assets/images/cog-solid.svg" alt="cog-solid Font Awesome icon">
          Settings
        </a>

      </div>
    </div>

    <div class="content">

      <div class="top-bar">
        <div class="breadcrumb">
          Home / Locations
        </div>

        <div class="options">
          <input type="search" name="" value="">
          <img src="assets/images/user-solid.svg" alt="user-solid font awesome icon"><span><?php echo $_SESSION['auth_uname'] ?></span>

          <a href="logout">
            <img src="assets/images/sign-out-alt-solid.svg" alt="sign-out-alt-solid Font Awesome icon">
          </a>

          <img src="assets/images/bell-solid.svg" alt="bell-solid Font Awesome icon">
        </div>

      </div>

      <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newLocation')">ADD A LOCATION</button>

      <div class="modal" id="newLocation">

        <div class="modal-dialog">
            <div class="modal-head">
              <h2>Add a Location</h2>
            </div>
            <div class="modal-body">
              <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/location/addLocation" method="post">
                <?php
                    echo CSRF::createToken();
                ?>

                <label for="location_name">Location Name</label>
                <input type="text" required name="location_name" placeholder="Location name">

                <label for="location_description">Location Description</label>
                <textarea name="location_description" rows="3" placeholder="Location description"></textarea>

                <label for="location_status">Location Status</label>
                <input type="text" required name="location_status" placeholder="Location status">

                <input type="submit" name="submit" value="Add Location">
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="close-modal-btn" onclick="closeModal('#newLocation')" name="button">Close &times;</button>
            </div>
        </div>

      </div>

      <?php
        foreach ($getLocationsResponse['data'] as $singleLocationInfo) {
      ?>
        <div class="modal" id="editLocation<?php echo $singleLocationInfo['entry_id']; ?>">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Edit Location : <?php echo $singleLocationInfo['location_name']; ?></h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/location/editLocation" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>
                  <input type="hidden" name="location_id" value="<?php echo $singleLocationInfo['entry_id'] ?>">

                  <label for="location_name">Location Name</label>
                  <input type="text" required name="location_name" placeholder="Location name" value="<?php echo $singleLocationInfo['location_name'] ?>">

                  <label for="location_description">Location Description</label>
                  <textarea name="location_description" rows="3" placeholder="Location description"><?php echo $singleLocationInfo['location_description'] ?></textarea>

                  <label for="location_status">Location Status</label>
                  <input type="text" required name="location_status" placeholder="Location status" value="<?php echo $singleLocationInfo['location_status'] ?>">


                  <input type="submit" name="submit" value="Edit Location">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#editLocation<?php echo $singleLocationInfo['entry_id']; ?>')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <div class="modal" id="deleteLocation<?php echo $singleLocationInfo['entry_id']; ?>">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Delete location: <?php echo $singleLocationInfo['location_name'] ?></h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/location/deleteLocation" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>
                  <p>Are you sure you want to delete this location?</p>
                  <input type="hidden" name="location_id" value="<?php echo $singleLocationInfo['entry_id'] ?>">

                  <input type="submit" name="submit" value="Yes I am">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#deleteLocation<?php echo $singleLocationInfo['entry_id']; ?>')" name="button">No &times;</button>
              </div>
          </div>

        </div>

      <?php
        }
      ?>


      <table class="table-data glassmorphic">
        <thead>
          <th colspan="10">
            <h3>Locations Present</h3>
          </th>
        </thead>
        <thead>
          <th>Entry ID</th>
          <th>Location Name</th>
          <th>Location Description</th>
          <th>Location Status</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php
            if ($getLocationsResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="9">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
          }else if($getLocationsResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="9">
                <h3>No locations present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getLocationsResponse['data'] as $singleLocationInfo) {
            ?>
            <tr>
              <td><?php echo $singleLocationInfo['entry_id'] ?></td>
              <td><?php echo $singleLocationInfo['location_name'] ?></td>
              <td><?php echo $singleLocationInfo['location_description'] ?></td>
              <td><?php echo $singleLocationInfo['location_status'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editLocation<?php echo $singleLocationInfo['entry_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deleteLocation<?php echo $singleLocationInfo['entry_id']; ?>')">Delete</button>
              </td>
            </tr>
            <?php
            }
          }
          ?>
        </tbody>
      </table>

    </div>

  </div>
  <script src="assets/js/app.min.js" charset="utf-8"></script>
</body>
</html>
