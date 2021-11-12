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

$Vendor = new Vendor();

$getVendorsResponse = $Vendor->getVendors();
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
  <title>Vendors</title>
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

        <a href="vendors" class="navigation-item active">
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

        <button type="button" name="button" class="new-subscription-btn" onclick="openModal('#newVendor')">ADD A VENDOR</button>

        <div class="modal" id="newVendor">

          <div class="modal-dialog">
              <div class="modal-head">
                <h2>Add a Vendor</h2>
              </div>
              <div class="modal-body">
                <form action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/vendor/addVendor" method="post">
                  <?php
                      echo CSRF::createToken();
                  ?>

                  <div>
                    <label for="vendor_name">Vendor Name</label>
                    <input type="text" required name="vendor_name" placeholder="Vendor name">
                  </div>

                  <div>
                    <label for="vendor_email">Vendor Email</label>
                    <input type="email" required name="vendor_email" placeholder="Vendor email">
                  </div>

                  <div>
                    <label for="vendor_email">Vendor Mobile</label>
                    <input type="tel" required name="vendor_mobile" placeholder="Vendor mobile">
                  </div>

                  <div class="full-grid">
                    <label for="vendor_email">Vendor Description</label>
                    <textarea name="vendor_description" rows="3" placeholder="Vendor description"></textarea>
                  </div>

                  <input type="submit" name="submit" class="full-grid" value="Add Vendor">
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="close-modal-btn" onclick="closeModal('#newVendor')" name="button">Close &times;</button>
              </div>
          </div>

        </div>

        <?php
          foreach ($getVendorsResponse['data'] as $singleVendorInfo) {
        ?>
          <div class="modal" id="editVendor<?php echo $singleVendorInfo['vendor_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Edit Vendor: <?php echo $singleVendorInfo['vendor_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/vendor/editVendor" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <input type="hidden" name="vendor_id" value="<?php echo $singleVendorInfo['vendor_id'] ?>">

                    <div>
                      <label for="vendor_name">Vendor Name</label>
                      <input type="text" required name="vendor_name" placeholder="Vendor name" value="<?php echo $singleVendorInfo['vendor_name'] ?>">
                    </div>

                    <div>
                      <label for="vendor_email">Vendor Email</label>
                      <input type="email" required name="vendor_email" placeholder="Vendor email" value="<?php echo $singleVendorInfo['vendor_email'] ?>">
                    </div>

                    <div>
                      <label for="vendor_email">Vendor Mobile</label>
                      <input type="tel" required name="vendor_mobile" placeholder="Vendor mobile" value="<?php echo $singleVendorInfo['vendor_mobile'] ?>">
                    </div>

                    <div class="full-grid">
                      <label for="vendor_email">Vendor Description</label>
                      <textarea name="vendor_description" rows="3" placeholder="Vendor description"><?php echo $singleVendorInfo['vendor_description'] ?></textarea>
                    </div>

                    <input type="submit" name="submit" class="full-grid" value="Submit Edits Vendor">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#editVendor<?php echo $singleVendorInfo['vendor_id']; ?>')" name="button">Close &times;</button>
                </div>
            </div>

          </div>
          <div class="modal" id="deleteVendor<?php echo $singleVendorInfo['vendor_id']; ?>">

            <div class="modal-dialog">
                <div class="modal-head">
                  <h2>Delete vendor: <?php echo $singleVendorInfo['vendor_name'] ?></h2>
                </div>
                <div class="modal-body">
                  <form class="" action="<?php echo $_ENV['APP_URL'] ?>/app/formhandlers/vendor/deleteVendor" method="post">
                    <?php
                        echo CSRF::createToken();
                    ?>
                    <p class="full-grid">Are you sure you want to delete this vendor?</p>
                    <input type="hidden" name="vendor_id" value="<?php echo $singleVendorInfo['vendor_id'] ?>">

                    <input type="submit" name="submit" class="full-grid" value="Yes I am">
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="close-modal-btn" onclick="closeModal('#deleteVendor<?php echo $singleVendorInfo['vendor_id']; ?>')" name="button">No &times;</button>
                </div>
            </div>

          </div>
        <?php
          }
        ?>

      <table class="table-data glassmorphic">
        <thead>
          <th colspan="7">
            <h2>Vendors Present</h2>
          </th>
        </thead>
        <thead>
          <th>Vendor ID</th>
          <th>Vendor Name</th>
          <th>Vendor Email</th>
          <th>Vendor Phonenumber</th>
          <th>Vendor Description</th>
          <th>Actions</th>
        </thead>
        <tbody>
          <?php
            if ($getVendorsResponse['response'] == '500') {
          ?>
            <tr>
              <td colspan="7">
                <h3>There has been an error retrieving the records. It had been recorded</h3>
              </td>
            </tr>
          <?php
          }else if($getVendorsResponse['response'] == '204') {
          ?>
            <tr>
              <td colspan="7">
                <h3>No vendors present in the system</h3>
              </td>
            </tr>
          <?php
          }else {
            foreach ($getVendorsResponse['data'] as $singleVendorInfo) {
            ?>
            <tr>
              <td><?php echo $singleVendorInfo['vendor_id'] ?></td>
              <td><?php echo $singleVendorInfo['vendor_name'] ?></td>
              <td><?php echo $singleVendorInfo['vendor_email'] ?></td>
              <td><?php echo $singleVendorInfo['vendor_mobile'] ?></td>
              <td><?php echo $singleVendorInfo['vendor_description'] ?></td>
              <td>
                <button class="action-edit-btn" onclick="openModal('#editVendor<?php echo $singleVendorInfo['vendor_id']; ?>')">Edit</button>
                <button class="action-delete-btn" onclick="openModal('#deleteVendor<?php echo $singleVendorInfo['vendor_id']; ?>')">Delete</button>
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
