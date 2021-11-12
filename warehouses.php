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

use app\Warehouse;

$Warehouse = new Warehouse();

$getWarehousesResponse = $Warehouse->getWarehouses();

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
  <link rel="stylesheet" href="assets/css/select2.min.css" />
  <title>Warehouses</title>
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

        <a href="powdercoating" class="navigation-item">
          <img src="assets/images/industry-solid.svg" alt="industry-solid Font Awesome icon">
          Powder Coating
        </a>

        <h3>Office</h3>
        <a href="locations" class="navigation-item">
          <img src="assets/images/map-marker-alt-solid.svg" alt="map-marker-alt-solid Font Awesome icon">
          Locations
        </a>

        <a href="warehouses" class="navigation-item active">
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

        <a href="purchase" class="navigation-item">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Purchases
        </a>

        <a href="purchaseorder" class="navigation-item">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Purchase Order
        </a>

        <a href="expense" class="navigation-item">
          <img src="assets/images/chess-queen-solid.svg" alt="chess-queen-solid Font Awesome icon">
          Expenses
        </a>

      </div>
    </div>

    <div class="content">
      <div class="top-bar">
        <div class="breadcrumb">
          Home / Warehouses
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



      <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newWarehouse')">ADD A WAREHOUSE</button>

      <div class="modal" id="newWarehouse">

        <div class="modal-dialog">
            <div class="modal-head">
              <h2>Add a Warehouse</h2>
            </div>
            <div class="modal-body">
              <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/warehouse/addWarehouse" method="post">
                <?php
                    echo CSRF::createToken();
                ?>

                <div>
                  <label for="warehouse_name">Warehouse Name</label>
                  <input type="text" required name="warehouse_name" placeholder="Warehouse name">
                </div>

                <div>
                  <label for="warehouse_description">Warehouse Description</label>
                  <textarea name="warehouse_description" rows="3" placeholder="Warehouse description"></textarea>
                </div>

                <div>
                  <label for="warehouse_status">Warehouse Status</label>
                  <input type="text" required name="warehouse_status" placeholder="Warehouse status">
                </div>

                <div>
                  <label for="location_id">Choose a location</label>
                  <select name="location_id">
                    <?php
                      if (count($getLocationsResponse['data'])<1) {
                        ?>
                          <option disabled selected value="">
                            No locations to pick from
                          </option>
                        <?php
                      }else {
                        foreach ($getLocationsResponse['data'] as $singleLocationInfo) {
                      ?>
                          <option value="<?php echo $singleLocationInfo['location_id'] ?>">
                            <?php echo $singleLocationInfo['location_name'] ?>
                          </option>
                      <?php
                        }
                      }
                    ?>
                  </select>
                </div>

                <input type="submit" name="submit" class="full-grid" value="Add Warehouse">
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="close-modal-btn" onclick="closeModal('#newWarehouse')" name="button">Close &times;</button>
            </div>
        </div>

      </div>

      <?php
        foreach ($getWarehousesResponse['data'] as $singleWarehouseInfo) {

      ?>
        <div class="modal" id="editWarehouse<?php echo $singleWarehouseInfo['warehouse_id']; ?>">

          <div class="modal-dialog">

              <div class="modal-head">
                <h2>Edit warehouse: <u><?php echo $singleWarehouseInfo['warehouse_name']; ?></u></h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/warehouse/editWarehouse" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>
                  <input type="hidden" name="warehouse_id" value="<?php echo $singleWarehouseInfo['warehouse_id'] ?>">

                  <div>
                    <label for="location_name">Warehouse Name</label>
                    <input type="text" required name="warehouse_name" placeholder="Warehouse name" value="<?php echo $singleWarehouseInfo['warehouse_name'] ?>">
                  </div>

                  <div>
                    <label for="warehouse_description">Warehouse Description</label>
                    <textarea name="warehouse_description" rows="3" placeholder="Warehouse description"><?php echo $singleWarehouseInfo['warehouse_description']; ?></textarea>
                  </div>

                  <div>
                    <label for="warehouse_status">Warehouse Status</label>
                    <input type="text" required name="warehouse_status" placeholder="Warehouse status" value="<?php echo $singleWarehouseInfo['warehouse_status']; ?>">
                  </div>

                  <div class="">
                    <select name="location_id">
                        <?php
                          foreach ($getLocationsResponse['data'] as $singleLocationInfo) {
                            if ( $singleLocationInfo['location_id'] == $singleWarehouseInfo['location_id'] ) {
                              ?>
                                  <option selected value="<?php echo $singleLocationInfo['location_id'] ?>">
                                    <?php echo $singleLocationInfo['location_name']; ?>(CHOSEN)
                                  </option>
                              <?php
                              } else {
                              ?>
                                  <option value="<?php echo $singleLocationInfo['location_id'] ?>">
                                    <?php echo $singleLocationInfo['location_name'] ?>
                                  </option>
                              <?php
                            }
                          }
                        ?>
                    </select>
                  </div>


                  <input type="submit" class="full-grid" name="submit" value="Submit Edit Warehouse">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#editWarehouse<?php echo $singleWarehouseInfo['warehouse_id']; ?>')" name="button">Close &times;</button>
              </div>
          </div>

        </div>
        <div class="modal" id="deleteWarehouse<?php echo $singleWarehouseInfo['warehouse_id']; ?>">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Delete warehouse: <?php echo $singleWarehouseInfo['warehouse_name'] ?></h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/warehouse/deleteWarehouse" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>
                  <p class="full-grid">Are you sure you want to delete this warehouse?</p>
                  <input type="hidden" name="warehouse_id" value="<?php echo $singleWarehouseInfo['warehouse_id'] ?>">

                  <input type="submit" class="full-grid" name="submit" value="Yes I am">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#deleteWarehouse<?php echo $singleWarehouseInfo['warehouse_id']; ?>')" name="button">No &times;</button>
              </div>
          </div>

        </div>
      <?php
        }
      ?>


      <table class="table-data glassmorphic">
        <thead>
          <th colspan="10">
            <h3>Warehouses Present</h3>
          </th>
        </thead>
        <thead>
          <th>Entry ID</th>
          <th>Warehouse Name</th>
          <th>Warehouse Description</th>
          <th>Location Name</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php
            if ($getWarehousesResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="10">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
          }else if($getWarehousesResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="10">
                <h3>No warehouses present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getWarehousesResponse['data'] as $singleWarehouseInfo) {
            ?>
            <tr>
              <td><?php echo $singleWarehouseInfo['warehouse_id'] ?></td>
              <td><?php echo $singleWarehouseInfo['warehouse_name'] ?></td>
              <td><?php echo $singleWarehouseInfo['warehouse_description'] ?></td>
              <td><?php echo $singleWarehouseInfo['location_name'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editWarehouse<?php echo $singleWarehouseInfo['warehouse_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deleteWarehouse<?php echo $singleWarehouseInfo['warehouse_id']; ?>')">Delete</button>
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
  <script type="text/javascript" src="assets/js/jquery.min.js"></script>
  <script type="text/javascript" src="assets/js/select2.min.js"></script>
  <script>
  $(document).ready(function(){
    $(function () {
        $("select").select2();
      });
  })
  </script>
</body>
</html>
