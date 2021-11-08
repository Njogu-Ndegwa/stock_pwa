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

use app\Vendor;

use app\Material;

use app\Location;

$Material = new Material();

$getMaterialsResponse = $Material->getMaterials();

$Vendor = new Vendor();

$getVendorsResponse = $Vendor->getVendors();

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
  <title>Inventory</title>
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
        <a href="locations" class="navigation-item">
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

        <a href="inventory" class="navigation-item  active">
          <img src="assets/images/money-check-alt-solid.svg" alt="money-check-alt-solid Font Awesome icon">
          Inventory
        </a>

      </div>
    </div>

    <div class="content">

      <div class="top-bar">
        <div class="breadcrumb">
          Home / Categories
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

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newMaterial')">
          ADD MATERIAL
        </button>

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newStock')">
          STOCK IN
        </button>

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newStock')">
          INVENTORY ACQUISITION
        </button>

        <div class="modal" id="newStock">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Stock In An Item</h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/inventory/stockIn" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                  <label for="item_name">Item name</label>
                  <select name="item_name" onchange="updateItemCode(this)">
                    <?php
                      if ($getMaterialsResponse['response'] == '204') {
                    ?>
                        <option disabled selected>No Items Present</option>
                    <?php
                    }elseif ($getMaterialsResponse['response'] == '500') {
                    ?>
                        <option disabled selected>Error in fetching the items</option>
                    <?php
                    }else {
                      foreach ($getMaterialsResponse['data'] as $singleMaterialInfo) {
                    ?>
                        <option value="">Choose an item</option>
                        <option data-code="<?php echo $singleMaterialInfo['material_code'] ?>" value="<?php echo $singleMaterialInfo['material_id'] ?>">
                          <?php echo $singleMaterialInfo['item_name'] ?>
                        </option>
                    <?php
                      }
                    }
                    ?>
                  </select>

                  <label for="item_code">Item code</label>
                  <input type="text" id="itemCodeStockIn" required name="item_code" placeholder="Choose item first">

                  <label for="vendor_id">Vendor</label>
                  <select name="vendor_id">
                    <?php
                      if ($getVendorsResponse['response'] == '204') {
                      ?>
                        <option selected disabled value="">There are no vendors present in the system</option>
                      <?php
                      }else {
                        foreach ($getVendorsResponse['data'] as $vendorInfo) {
                      ?>
                          <option value="<?php echo $vendorInfo['vendor_id'] ?>">
                            <?php echo $vendorInfo['vendor_name'] ?>
                          </option>
                      <?php
                        }
                      }
                    ?>
                  </select>

                  <label for="location_id">Location</label>
                  <select name="location_id" onchange="getLocationWarehouses(this)">
                    <?php
                      if ($getLocationsResponse['response'] == '204') {
                    ?>
                        <option disabled selected>No locations present</option>
                    <?php
                  }elseif ($getLocationsResponse['response'] == '500') {
                    ?>
                        <option disabled selected>Error in fetching the locations</option>
                    <?php
                    }else {
                      ?>
                        <option disabled selected value="">Choose a location</option>
                      <?php
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

                  <label for="warehouse_id">Warehouse</label>
                  <select name="warehouse_id" id="warehouseIDStockIn">
                    <option disabled selected>Choose a location first</option>
                  </select>

                  <div class="grid">
                    <div>
                      <label for="lpo">LPO</label>
                      <input type="number" required name="lpo" placeholder="LPO">
                    </div>
                    <div>
                      <label for="invoice">Invoice</label>
                      <input type="number" required name="invoice" placeholder="Invoice">
                    </div>
                  </div>

                  <label for="delivery_note_no">Delivery Note Number</label>
                  <input type="number" required name="delivery_note_no" placeholder="Delivery note number">

                  <div class="grid">
                    <div>
                      <label for="price_per_item">Price per item</label>
                      <input type="number" required name="price_per_item" placeholder="Price per item">
                    </div>
                    <div>
                      <label for="cost_per_item">Cost per item</label>
                      <input type="number" required name="cost_per_item" placeholder="Cost per item">
                    </div>
                  </div>

                  <div class="grid">
                    <div>
                      <label for="minimum_threshold">Min threshold</label>
                      <input type="number" required name="minimum_threshold" placeholder="Minimum threshold">
                    </div>
                    <div>
                      <label for="maximum_threshold">Max threshold</label>
                      <input type="number" required name="maximum_threshold" placeholder="Maximum threshold">
                    </div>
                  </div>

                  <label for="vehicle_plate">Vehicle Plate Number</label>
                  <input type="text" required name="vehicle_plate" placeholder="Vehicle plate number">

                  <div class="grid">
                    <div>
                      <label for="start_mileage">Start mileage</label>
                      <input type="number" required name="start_mileage" placeholder="Start mileage">
                    </div>
                    <div>
                      <label for="stop_mileage">Stop mileage</label>
                      <input type="number" required name="stop_mileage" placeholder="Stop mileage">
                    </div>
                  </div>

                  <label for="quantity">Quantity</label>
                  <input type="number" required name="quantity" placeholder="Quantity">

                  <label for="powder">Powder</label>
                  <input type="text" required name="powder" placeholder="Powder"/>

                  <label for="color">Color</label>
                  <input type="text" required name="color" placeholder="Powder"/>

                  <label for="material">Material</label>
                  <input type="text" name="material" placeholder="Material">

                  <label for="image_url">Image URL</label>
                  <input type="url" name="image_url" placeholder="Image URL">

                  <input type="submit" name="submit" value="Stock In">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newStock')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <div class="modal" id="newMaterial">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Add a Material</h2>
              </div>
              <div class="modal-body">
                <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/inventory/addMaterial" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                  <label for="item_name">Item name</label>
                  <input type="text" required name="item_name" placeholder="Item name">

                  <div class="grid">
                    <div class="">
                      <label for="item_code">Item code</label>
                      <input type="text" required name="item_code" placeholder="Item code">
                    </div>
                    <div class="">
                      <label for="vendor_company">Vendor</label>
                      <select name="vendor_company">
                        <?php
                          if ($getVendorsResponse['response'] == '204') {
                          ?>
                            <option selected disabled value="">There are no vendors present in the system</option>
                          <?php
                          }else {
                            foreach ($getVendorsResponse['data'] as $vendorInfo) {
                          ?>
                              <option value="<?php echo $vendorInfo['vendor_name'] ?>">
                                <?php echo $vendorInfo['vendor_name'] ?>
                              </option>
                          <?php
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>

                  <label for="serial_number">Serial</label>
                  <input type="text" required name="serial_number" placeholder="Serial number">

                  <label for="item_type">Item type</label>
                  <select name="item_type" onchange="changeUnit(this)">
                    <option value="Hardware">Hardware</option>
                    <option value="Aluminum">Aluminum</option>
                    <option value="Powder">Powder</option>
                  </select>

                  <label for="quantity">Quantity<span id="unit">(Units)</span> </label>
                  <input type="number" required name="quantity" placeholder="Category status">

                  <label for="image_url">Image URL</label>
                  <input type="url" name="image_url" placeholder="Image URL">

                  <div class="grid">
                    <div>
                      <label for="minimum_threshold">Min threshold</label>
                      <input type="number" required name="minimum_threshold" placeholder="Minimum threshold">
                    </div>
                    <div>
                      <label for="maximum_threshold">Max threshold</label>
                      <input type="number" required name="maximum_threshold" placeholder="Maximum threshold">
                    </div>
                  </div>

                  <label for="pricing">Pricing</label>
                  <input type="number" required name="pricing" placeholder="Pricing">

                  <input type="submit" name="submit" value="Add Item">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newMaterial')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <?php
          foreach ($getMaterialsResponse['data'] as $singleMaterialInfo) {
        ?>
          <div class="modal" id="editMaterial<?php echo $singleMaterialInfo['material_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Edit Item: <?php echo $singleMaterialInfo['item_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/inventory/editItem" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <input type="hidden" name="material_id" value="<?php echo $singleMaterialInfo['material_id'] ?>">

                    <label for="item_name">Item name</label>
                    <input type="text" required name="item_name" placeholder="Item name" value="<?php echo $singleMaterialInfo['item_name']; ?>">

                    <label for="item_code">Item code</label>
                    <input type="text" required name="item_code" placeholder="Item code" value="<?php echo $singleMaterialInfo['material_code'] ?>">

                    <label for="serial_number">Serial</label>
                    <input type="text" required name="serial_number" placeholder="Serial number" value="<?php echo $singleMaterialInfo['serial_number'] ?>">

                    <label for="item_type">Item type</label>
                    <select name="item_type" onchange="changeUnit(this)">
                      <?php
                        $itemType = ['Hardware', 'Aluminum', 'Powder'];

                        for ($i=0; $i < 3; $i++) {
                          if ($singleMaterialInfo['material_type'] == $itemType[$i]) {
                      ?>
                          <option selected value="<?php echo $singleMaterialInfo['material_type'] ?>">
                            <?php echo $itemType[$i] ?>(CHOSEN)
                          </option>
                      <?php
                        }else {
                      ?>
                          <option value="<?php echo $itemType[$i] ?>">
                            <?php echo $itemType[$i]; ?>
                          </option>
                      <?php
                        }
                      }
                      ?>
                    </select>

                    <label for="quantity">Quantity<span id="unit">(Units)</span> </label>
                    <input type="number" required name="quantity" placeholder="Category status" value="<?php echo $singleMaterialInfo['quantity']; ?>">

                    <label for="image_url">Image URL</label>
                    <input type="url" name="image_url" placeholder="Image URL" value="<?php echo $singleMaterialInfo['image_url']; ?>">

                    <div class="grid">
                      <div>
                        <label for="minimum_threshold">Min threshold</label>
                        <input type="number" required name="minimum_threshold" placeholder="Minimum threshold" value="<?php echo $singleMaterialInfo['min_threshold']; ?>">
                      </div>
                      <div>
                        <label for="maximum_threshold">Max threshold</label>
                        <input type="number" required name="maximum_threshold" placeholder="Maximum threshold" value="<?php echo $singleMaterialInfo['max_threshold']; ?>">
                      </div>
                    </div>

                    <label for="pricing">Pricing</label>
                    <input type="number" required name="pricing" placeholder="Pricing" value="<?php echo $singleMaterialInfo['pricing']; ?>">

                    <input type="submit" name="submit" value="Edit Item">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#editMaterial<?php echo $singleMaterialInfo['material_id']; ?>')" name="button">Close &times;</button>
                </div>
            </div>

          </div>
          <div class="modal" id="deleteMaterial<?php echo $singleMaterialInfo['material_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Delete item: <?php echo $singleMaterialInfo['item_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/inventory/deleteItem" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <p>Are you sure you want to delete this item?</p>
                    <input type="hidden" name="material_id" value="<?php echo $singleMaterialInfo['material_id'] ?>">

                    <input type="submit" name="submit" value="Yes I am">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#deleteMaterial<?php echo $singleMaterialInfo['material_id']; ?>')" name="button">No &times;</button>
                </div>
            </div>

          </div>
        <?php
          }
        ?>

      <table class="table-data glassmorphic">
        <thead>
          <th>Item Name</th>
          <th>Item Code</th>
          <th>Item Type</th>
          <th>Image URL</th>
          <th>Min. Threshold</th>
          <th>Max. Threshold</th>
          <th>Pricing</th>
          <th>Quantity</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php
            if ($getMaterialsResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="10">
                <h3>There has been an error retrieving the records. It had been logged</h3>
              </td>
            </tr>
          <?php
        }else if($getMaterialsResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="10">
                <h3>No items present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getMaterialsResponse['data'] as $singleMaterialInfo) {
            ?>
            <tr>
              <td><?php echo $singleMaterialInfo['item_name'] ?></td>
              <td><?php echo $singleMaterialInfo['material_code'] ?></td>
              <td><?php echo $singleMaterialInfo['material_type'] ?></td>
              <td>
                <a href="<?php echo $singleMaterialInfo['image_url'] ?>" target="_blank">
                  View Image
                </a>
              </td>
              <td><?php echo $singleMaterialInfo['min_threshold'] ?></td>
              <td><?php echo $singleMaterialInfo['max_threshold'] ?></td>
              <td><?php echo $singleMaterialInfo['pricing'] ?></td>
              <td><?php echo $singleMaterialInfo['quantity'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editMaterial<?php echo $singleMaterialInfo['material_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deleteMaterial<?php echo $singleMaterialInfo['material_id']; ?>')">Delete</button>
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
